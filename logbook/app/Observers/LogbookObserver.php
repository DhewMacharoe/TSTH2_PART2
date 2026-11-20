<?php

namespace App\Observers;

use App\Models\Logbook;

class LogbookObserver
{
    /**
     * Handle the Logbook "created" event.
     */
    public function created(Logbook $logbook): void
    {
        if ($logbook->status === 'approved') {
            $this->recalculateRollup($logbook->task_id);
        }
    }

    public function updated(Logbook $logbook): void
    {
        if ($logbook->isDirty('status') || ($logbook->status === 'approved' && $logbook->isDirty('progress'))) {
            $this->recalculateRollup($logbook->task_id);
        }
    }

    public function deleted(Logbook $logbook): void
    {
        if ($logbook->status === 'approved') {
            $this->recalculateRollup($logbook->task_id);
        }
    }

    private function recalculateRollup($taskId)
    {
        if (!$taskId) return;

        $task = \App\Models\Task::find($taskId);
        if (!$task) return;

        // 1. Update Task verified_progress (using current_progress which gets latest approved logbook)
        $task->verified_progress = $task->current_progress;
        if ($task->verified_progress >= ($task->target_progress ?? 100)) {
            $task->status = 'completed';
        }
        $task->save();

        $subTarget = $task->subTarget;
        if (!$subTarget) return;

        // 2. Update SubTarget progress
        $subTargetTasks = $subTarget->tasks;
        $totalTaskWeight = $subTargetTasks->sum('weight');
        if ($totalTaskWeight > 0) {
            $tasksProgress = $subTargetTasks->sum(function($t) {
                return ($t->verified_progress ?? 0) * ($t->weight ?? 0) / 100;
            });
        } else {
            $tasksProgress = $subTargetTasks->avg('verified_progress') ?? 0;
        }
        $subTarget->progress = min(100, max(0, (int) round($tasksProgress)));
        $subTarget->save();

        $target = $subTarget->target;
        if (!$target) return;

        // 3. Update Target progress
        $targetSubTargets = $target->subTargets;
        $totalSubTargetPercentage = $targetSubTargets->sum('percentage');
        if ($totalSubTargetPercentage > 0) {
            $subTargetsProgress = $targetSubTargets->sum(function($st) {
                return ($st->progress ?? 0) * ($st->percentage ?? 0) / 100;
            });
        } else {
            $subTargetsProgress = $targetSubTargets->avg('progress') ?? 0;
        }
        $target->target_progress = min(100, max(0, (int) round($subTargetsProgress)));
        $target->save();

        $project = $target->project;
        if (!$project) return;

        // 4. Update Project progress
        $projectTargets = $project->targets;
        $totalTargetWeight = $projectTargets->sum('weight');
        if ($totalTargetWeight > 0) {
            $targetsProgress = $projectTargets->sum(function($t) {
                return ($t->target_progress ?? 0) * ($t->weight ?? 0) / 100;
            });
        } else {
            $targetsProgress = $projectTargets->avg('target_progress') ?? 0;
        }
        $project->progress = min(100, max(0, (int) round($targetsProgress)));
        $project->save();
    }
}
