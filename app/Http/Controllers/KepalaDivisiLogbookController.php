<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Http\Request;

class KepalaDivisiLogbookController extends Controller
{
    /**
     * Menampilkan logbook karyawan dalam divisi,
     * dipisahkan antara pekerjaan ditugaskan dan mandiri.
     */
    public function index()
    {
        $user = auth()->user();

        $baseQuery = Logbook::with([
            'user',
            'task.target',
            'task.subTarget',
            'task.assignedBy',
            'reviewer',
        ])
        ->whereHas('user', function ($query) use ($user) {
            $query
                ->where('department_id', $user->department_id)
                ->where('division_id', $user->division_id)
                ->where('role', 'employee');
        });

        // Logbook dari pekerjaan yang ditugaskan
        // oleh Kepala Divisi yang sedang login.
        $assignedLogbooks = (clone $baseQuery)
            ->whereNotNull('task_id')
            ->whereHas('task', function ($taskQuery) use ($user) {
                $taskQuery
                    ->where('assigned_by', $user->id)
                    ->where('department_id', $user->department_id)
                    ->where('division_id', $user->division_id);
            })
            ->latest('date')
            ->latest('id')
            ->get();

        // Logbook mandiri yang tidak memiliki pekerjaan.
        $independentLogbooks = (clone $baseQuery)
            ->whereNull('task_id')
            ->where('logbook_type', 'independent')
            ->latest('date')
            ->latest('id')
            ->get();

        return view(
            'kepala-divisi.logbooks.index',
            compact('assignedLogbooks', 'independentLogbooks')
        );
    }

    /**
     * Menampilkan detail logbook.
     */
    public function show(Logbook $logbook)
    {
        $user = auth()->user();

        $this->authorizeLogbook($logbook, $user);

        $logbook->load([
            'user',
            'task.target',
            'task.subTarget',
            'task.assignedBy',
            'reviewer',
            'comments.user',
        ]);

        return view(
            'kepala-divisi.logbooks.show',
            compact('logbook')
        );
    }

    /**
     * Menyetujui atau meminta revisi logbook.
     */
    public function review(Request $request, Logbook $logbook)
    {
        $user = auth()->user();

        $this->authorizeLogbook($logbook, $user);

        $validated = $request->validate([
            'action' => [
                'required',
                'in:approve,revision',
            ],
            'review_note' => [
                'nullable',
                'string',
            ],
        ]);

        if (
            $validated['action'] === 'revision'
            && empty(trim($validated['review_note'] ?? ''))
        ) {
            return back()
                ->withErrors([
                    'review_note' => 'Catatan revisi wajib diisi.',
                ])
                ->withInput();
        }

        $task = $logbook->task;

        if ($validated['action'] === 'approve') {
            $logbook->update([
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
                'review_note' => $validated['review_note'] ?? null,
            ]);

            // Hanya logbook dari tugas yang memengaruhi progres tugas.
            // Diambil nilai progres dari logbook yang disetujui terbaru (misal hari 4 = 80%)
            if ($task) {
                $latestProgress = $task->fresh()->current_progress;
                $task->update([
                    'verified_progress' => $latestProgress,
                    'status' => $latestProgress >= $task->target_progress
                        ? 'completed'
                        : 'in_progress',
                ]);
            }

            return redirect()
                ->route('kepala_divisi.logbooks.show', $logbook)
                ->with('success', 'Logbook berhasil disetujui.');
        }

        $logbook->update([
            'status' => 'revision',
            'reviewed_by' => $user->id,
            'reviewed_at' => now(),
            'review_note' => $validated['review_note'],
        ]);

        // Logbook mandiri tidak mempunyai tugas untuk diubah.
        if ($task) {
            $task->update([
                'status' => 'revision',
            ]);
        }

        return redirect()
            ->route('kepala_divisi.logbooks.show', $logbook)
            ->with(
                'success',
                'Logbook dikembalikan untuk revisi.'
            );
    }

    /**
     * Memastikan logbook berada dalam kewenangan
     * Kepala Divisi yang sedang login.
     */
    private function authorizeLogbook(
        Logbook $logbook,
        $user
    ): void {
        $logbook->loadMissing('user', 'task');

        $isEmployeeInDivision = $logbook->user
            && $logbook->user->role === 'employee'
            && $logbook->user->department_id == $user->department_id
            && $logbook->user->division_id == $user->division_id;

        $isAssignedTask = $logbook->task
            && $logbook->task->assigned_by == $user->id
            && $logbook->task->department_id == $user->department_id
            && $logbook->task->division_id == $user->division_id;

        $isIndependentLogbook = !$logbook->task
            && $logbook->task_id === null
            && $logbook->logbook_type === 'independent';

        abort_unless(
            $isEmployeeInDivision
            && ($isAssignedTask || $isIndependentLogbook),
            403,
            'Anda tidak memiliki akses ke logbook ini.'
        );
    }
}
