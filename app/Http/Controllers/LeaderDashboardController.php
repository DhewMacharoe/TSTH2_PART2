<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Logbook;
use App\Models\Target;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeaderDashboardController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | FILTER
        |--------------------------------------------------------------------------
        */

        $filters = $this->validatedFilters($request);
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $preset = $filters['preset'];
        $filterMonth = $filters['filterMonth'];
        $filterYear = $filters['filterYear'];
        $diffDays = $filters['diffDays'];
        $groupingType = $filters['groupingType'];
        $groupingLabel = $filters['groupingLabel'];
        $departmentId = $filters['department_id'];
        $divisionId = $filters['division_id'];
        $targetId = $filters['target_id'];
        $subTargetId = $filters['sub_target_id'];
        $employeeId = $filters['employee_id'];
        $activeSection = $filters['section'];
        $asOfDate = $filters['asOfDate'];
        $monitorDate = $filters['monitorDate'];
        $selectedYear = $filters['year'];

        /*
        |--------------------------------------------------------------------------
        | DATA KARYAWAN
        |--------------------------------------------------------------------------
        */

        $employees = User::query()
            ->where('role', 'employee')
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('id', $employeeId);
            })

            ->with([
                'assignedTasks' => function ($query) {
                    $query
                        ->with([
                            'target',
                            'subTarget.target',
                            'division',
                            'logbooks' => function ($query) {
                                $query->orderBy('date');
                            },
                        ]);
                }
            ])

            ->orderBy('name')
            ->get();
           $totalEmployees = $employees->count();

        /*
        |--------------------------------------------------------------------------
        | PLANNING VS AKTUAL PER KARYAWAN
        |--------------------------------------------------------------------------
        */

        $employeeMonitoring = [];

        foreach ($employees as $employee) {

            $totalPlanning = 0;
            $totalActual   = 0;
            $totalWeight   = 0;
            $totalTasks    = 0;

            foreach ($employee->assignedTasks as $task) {

                /*
                |--------------------------------------------------------------------------
                | FILTER DIVISI
                |--------------------------------------------------------------------------
                */

                if (
                    $divisionId &&
                    (int) $task->division_id !== (int) $divisionId
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | TASK YANG BELUM ADA PADA TANGGAL MONITORING
                |--------------------------------------------------------------------------
                */

                if (
                    $task->created_at &&
                    Carbon::parse($task->created_at)->gt($monitorDate)
                ) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | PLANNING
                |--------------------------------------------------------------------------
                */

                $workType = strtolower(
                    trim((string) $task->work_type)
                );

                /*
                |--------------------------------------------------------------------------
                | PEKERJAAN DADAKAN / OTHER
                |--------------------------------------------------------------------------
                |
                | Pekerjaan dadakan tidak mempunyai periode Sub Target.
                | Karena itu untuk monitoring dianggap memiliki
                | planning 100% setelah task diberikan.
                |
                |--------------------------------------------------------------------------
                */

                if (
                    in_array(
                        $workType,
                        [
                            'other',
                            'lainnya',
                            'dadakan',
                        ],
                        true
                    )
                ) {

                    $planning = 100;

                } else {

                    $planning = $this->calculatePlanning(
                        $task,
                        $monitorDate
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | AKTUAL
                |--------------------------------------------------------------------------
                |
                | Aktual menggunakan verified_progress karena nilai ini
                | merupakan progress yang sudah diverifikasi oleh
                | Kepala Divisi.
                |
                |--------------------------------------------------------------------------
                */

                $latestApprovedLog = $task->logbooks
                    ->where('status', 'approved')
                    ->filter(function ($lb) use ($endDate) {
                        return $lb->date && Carbon::parse($lb->date)->lte($endDate);
                    })
                    ->sortByDesc('date')
                    ->sortByDesc('id')
                    ->first();
                $actual = (float) ($latestApprovedLog?->progress ?? $task->verified_progress ?? 0);

                /*
                |--------------------------------------------------------------------------
                | BATAS AKTUAL 0 - 100
                |--------------------------------------------------------------------------
                */

                $actual = min(
                    max($actual, 0),
                    100
                );

                /*
                |--------------------------------------------------------------------------
                | ATURAN: TIDAK BOLEH PLANNING DI BAWAH AKTUAL
                |--------------------------------------------------------------------------
                */
                $planning = max((float) $planning, (float) $actual);
                $planning = min(max((float) $planning, 0), 100);

                /*
                |--------------------------------------------------------------------------
                | BOBOT TASK
                |--------------------------------------------------------------------------
                */

                $weight = (float) (
                    $task->weight ?? 0
                );

                /*
                | Jika bobot kosong atau 0,
                | gunakan bobot default 1.
                */

                if ($weight <= 0) {
                    $weight = 1;
                }

                /*
                |--------------------------------------------------------------------------
                | PERHITUNGAN BERBOBOT
                |--------------------------------------------------------------------------
                */

                $totalPlanning += $planning * $weight;
                $totalActual   += $actual * $weight;
                $totalWeight   += $weight;

                $totalTasks++;
            }

            /*
            |--------------------------------------------------------------------------
            | RATA-RATA PLANNING DAN AKTUAL
            |--------------------------------------------------------------------------
            */

            if ($totalWeight > 0) {

                $planning = round(
                    $totalPlanning / $totalWeight
                );

                $actual = round(
                    $totalActual / $totalWeight
                );

            } else {

                $planning = 0;
                $actual   = 0;
            }

            /*
            |--------------------------------------------------------------------------
            | BATAS NILAI & ATURAN PLANNING TIDAK BOLEH DI BAWAH AKTUAL
            |--------------------------------------------------------------------------
            */

            $planning = max((float) $planning, (float) $actual);

            $planning = min(
                max($planning, 0),
                100
            );

            $actual = min(
                max($actual, 0),
                100
            );

            /*
            |--------------------------------------------------------------------------
            | GAP
            |--------------------------------------------------------------------------
            |
            | Positif  = Aktual lebih tinggi dari Planning
            | 0        = Aktual sama dengan Planning
            | Negatif  = Aktual tertinggal dari Planning
            |
            |--------------------------------------------------------------------------
            */

            $gap = $actual - $planning;

            /*
            |--------------------------------------------------------------------------
            | STATUS
            |--------------------------------------------------------------------------
            */

            if ($totalTasks === 0) {

                $status = 'Belum Ada Task';

            } elseif ($gap > 5) {

                $status = 'Lebih Cepat';

            } elseif ($gap >= -5) {

                $status = 'Sesuai Rencana';

            } else {

                $status = 'Tertinggal';
            }

            /*
            |--------------------------------------------------------------------------
            | SIMPAN DATA MONITORING
            |--------------------------------------------------------------------------
            */

            $employeeMonitoring[] = [

                'employee' => $employee,

                'planning' => $planning,

                'actual' => $actual,

                'gap' => $gap,

                'status' => $status,

                'task_count' => $totalTasks,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DATA UNTUK CHART
        |--------------------------------------------------------------------------
        |
        | Data chart dibuat di Controller agar Blade tidak perlu melakukan
        | collect(), map(), atau array processing yang kompleks.
        |
        |--------------------------------------------------------------------------
        */

        $chartData = collect($employeeMonitoring)
            ->map(function ($item) {

                return [

                    'name' => $item['employee']->name,

                    'planning' => $item['planning'],

                    'actual' => $item['actual'],
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | DATA DEPARTEMEN & DIVISI
        |--------------------------------------------------------------------------
        */

        $divisions = Division::query()
            ->orderBy('name')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DATA PROJECT / TARGET
        |--------------------------------------------------------------------------
        */

        $projects = Target::query()
            ->with('subTargets')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereDate('period_start', '<=', $endDate->toDateString())
                      ->whereDate('period_end', '>=', $startDate->toDateString());
                })
                ->orWhere('status', 'active')
                ->orWhereNull('status')
                ->orWhereBetween('created_at', [$startDate, $endDate])
                ->orWhereYear('period_start', $startDate->year)
                ->orWhereYear('period_end', $endDate->year);
            })
            ->orderBy('title')
            ->get();

        if ($projects->isEmpty()) {
            $projects = Target::query()->with('subTargets')->orderBy('title')->get();
        }

        /*
        |--------------------------------------------------------------------------
        | HIERARCHICAL WEIGHTED AGGREGATION UNTUK PIE CHART
        |--------------------------------------------------------------------------
        | Mengikutsertakan seluruh target & sub target aktif.
        | Progress sub target dihitung dari rata-rata progres logbook approved
        | terbaru task (atau verified_progress jika logbook belum dibuat di periode ini).
        */

        $aggregationTargets = Target::query()
            ->when($targetId, function ($query) use ($targetId) {
                $query->where('id', $targetId);
            })
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->whereDate('period_start', '<=', $endDate->toDateString())
                      ->whereDate('period_end', '>=', $startDate->toDateString());
                })
                ->orWhere('status', 'active')
                ->orWhereNull('status')
                ->orWhereBetween('created_at', [$startDate, $endDate])
                ->orWhereYear('period_start', $startDate->year)
                ->orWhereYear('period_end', $endDate->year);
            })
            ->with([
                'division',
                'department',
                'subTargets' => function ($query) use ($subTargetId) {
                    $query->when($subTargetId, fn($q) => $q->where('id', $subTargetId));
                },
                'subTargets.tasks' => function ($query) use (
                    $divisionId,
                    $employeeId,
                    $monitorDate
                ) {
                    $query
                        ->when($divisionId, function ($query) use ($divisionId) {
                            $query->where('division_id', $divisionId);
                        })
                        ->when($employeeId, function ($query) use ($employeeId) {
                            $query->where('assigned_to', $employeeId);
                        })
                        ->where(function ($query) use ($monitorDate) {
                            $query->whereNull('created_at')
                                ->orWhere('created_at', '<=', $monitorDate);
                        })
                        ->with(['logbooks' => function ($query) {
                            $query->orderBy('date');
                        }]);
                },
            ])
            ->orderBy('title')
            ->get();

        if ($aggregationTargets->isEmpty()) {
            $aggregationTargets = Target::query()
                ->when($targetId, fn($q) => $q->where('id', $targetId))
                ->when($departmentId, fn($q) => $q->where('department_id', $departmentId))
                ->when($divisionId, fn($q) => $q->where('division_id', $divisionId))
                ->with([
                    'division',
                    'department',
                    'subTargets' => fn($q) => $q->when($subTargetId, fn($sq) => $sq->where('id', $subTargetId)),
                    'subTargets.tasks' => function ($query) use ($divisionId, $employeeId, $monitorDate) {
                        $query
                            ->when($divisionId, fn($q) => $q->where('division_id', $divisionId))
                            ->when($employeeId, fn($q) => $q->where('assigned_to', $employeeId))
                            ->where(function ($q) use ($monitorDate) {
                                $q->whereNull('created_at')->orWhere('created_at', '<=', $monitorDate);
                            })
                            ->with(['logbooks' => fn($q) => $q->orderBy('date')]);
                    },
                ])
                ->orderBy('title')
                ->get();
        }

        $targetMonitoring = [];

        foreach ($aggregationTargets as $target) {
            $targetProgress = 0;
            $subTargetDetails = [];

            if ($target->subTargets->isEmpty()) {
                continue;
            }

            $subTargetCount = $target->subTargets->count();

            foreach ($target->subTargets as $subTarget) {
                $verifiedTasks = $subTarget->tasks;
                $taskCount = $verifiedTasks->count();

                if ($taskCount > 0) {
                    $averageProgress = $verifiedTasks->avg(function ($task) use ($endDate) {
                        $latestApprovedLog = $task->logbooks
                            ->where('status', 'approved')
                            ->filter(function ($lb) use ($endDate) {
                                return $lb->date && Carbon::parse($lb->date)->lte($endDate);
                            })
                            ->sortByDesc('date')
                            ->sortByDesc('id')
                            ->first();

                        $prog = $latestApprovedLog
                            ? (float) $latestApprovedLog->progress
                            : (float) ($task->verified_progress ?? 0);

                        return min(max($prog, 0), 100);
                    });
                } else {
                    $averageProgress = (float) ($subTarget->progress ?? 0);
                }

                $percentage = (float) ($subTarget->percentage ?? 0);
                if ($percentage <= 0 && $subTargetCount > 0) {
                    $percentage = round(100 / $subTargetCount, 2);
                }
                $percentage = min(max($percentage, 0), 100);

                $contribution = $averageProgress * $percentage / 100;
                $targetProgress += $contribution;

                $subTargetDetails[] = [
                    'sub_target_id' => $subTarget->id,
                    'sub_target_name' => $subTarget->title,
                    'target_id' => $target->id,
                    'target_name' => $target->title,
                    'division_id' => $target->division_id,
                    'division_name' => $target->division?->name ?? 'Divisi Umum',
                    'department_id' => $target->department_id,
                    'department_name' => $target->department?->name ?? 'Departemen Umum',
                    'percentage' => round($percentage, 2),
                    'verified_task_count' => $taskCount,
                    'average_progress' => round($averageProgress, 2),
                    'progress' => round($averageProgress, 2),
                    'contribution' => round($contribution, 2),
                ];
            }

            $targetMonitoring[] = [
                'target_id' => $target->id,
                'target_name' => $target->title,
                'division_id' => $target->division_id,
                'division_name' => $target->division?->name ?? 'Divisi Umum',
                'department_id' => $target->department_id,
                'department_name' => $target->department?->name ?? 'Departemen Umum',
                'progress' => round(min(max($targetProgress, 0), 100), 2),
                'sub_targets' => $subTargetDetails,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | DATA GRAFIK PLANNING VS AKTUAL PER TARGET DAN TASK
        |--------------------------------------------------------------------------
        */

        $chartTargets = $projects;

        $chartTargetIds = $chartTargets->pluck('id')->all();
        $chartTargetLookup = array_fill_keys(
            array_map('intval', $chartTargetIds),
            true
        );
        $chartRows = [];

        foreach ($employees as $employee) {
            foreach ($employee->assignedTasks as $task) {
                $target = $task->target ?? $task->subTarget?->target;

                if (
                    $divisionId &&
                    (int) $task->division_id !== (int) $divisionId
                ) {
                    continue;
                }

                if (
                    $departmentId &&
                    (int) ($target?->department_id ?? 0) !== (int) $departmentId
                ) {
                    continue;
                }

                if (
                    $targetId &&
                    (int) ($target?->id ?? 0) !== (int) $targetId
                ) {
                    continue;
                }

                if (
                    $subTargetId &&
                    (int) ($task->sub_target_id ?? 0) !== (int) $subTargetId
                ) {
                    continue;
                }

                if (
                    $task->created_at &&
                    Carbon::parse($task->created_at)->gt($monitorDate)
                ) {
                    continue;
                }

                $workType = strtolower(trim((string) $task->work_type));
                $planning = in_array($workType, ['other', 'lainnya', 'dadakan'], true)
                    ? 100
                    : $this->calculatePlanning($task, $monitorDate);
                $latestApprovedLog = $task->logbooks
                    ->where('status', 'approved')
                    ->sortByDesc('date')
                    ->sortByDesc('id')
                    ->first();
                $actual = min(max((float) ($latestApprovedLog?->progress ?? $task->verified_progress ?? 0), 0), 100);
                // ATURAN: Tidak boleh planning di bawah aktual
                $planning = max((float) $planning, (float) $actual);
                $weight = (float) ($task->weight ?? 1);

                $chartRows[] = [
                    'target_id' => $target?->id ?? 0,
                    'target_name' => $target?->title ?? 'Tanpa Target',
                    'task_id' => $task->id,
                    'task_name' => $task->title,
                    'scope_name' => $task->subTarget?->title,
                    'division_id' => $task->division_id,
                    'employee_id' => $employee->id,
                    'planning' => min(max((float) $planning, 0), 100),
                    'actual' => $actual,
                    'weight' => $weight > 0 ? $weight : 1,
                ];
            }
        }

        $chartEmployees = User::query()
            ->where('role', 'employee')
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'division_id'])
            ->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'division_id' => $employee->division_id,
                ];
            })
            ->values();

        /*
        |--------------------------------------------------------------------------
        | DATA CHART MONITORING BERDASARKAN RENTANG WAKTU (SISTEM BRIMO)
        |--------------------------------------------------------------------------
        */

        $monthNames = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $dayNames = [
            0 => 'Minggu', 1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu',
            4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu',
        ];

        $timeBuckets = [];

        if ($groupingType === 'daily') {
            $cursor = $startDate->copy();
            while ($cursor->lte($endDate)) {
                $bStart = $cursor->copy()->startOfDay();
                $bEnd = $cursor->copy()->endOfDay();
                $mNum = (int) $cursor->month;
                $monthShort = substr($monthNames[$mNum] ?? '', 0, 3);
                $dayName = $dayNames[$cursor->dayOfWeek] ?? '';

                $label = $cursor->format('d') . ' ' . $monthShort;
                $shortName = $cursor->format('d') . ' ' . $monthShort;
                $fullName = $dayName . ', ' . $cursor->format('d') . ' ' . ($monthNames[$mNum] ?? '') . ' ' . $cursor->year;

                $subPoints = [
                    ['label' => 'Pagi (08:00)', 'time' => $bStart->copy()->setTime(8, 0)],
                    ['label' => 'Siang (12:00)', 'time' => $bStart->copy()->setTime(12, 0)],
                    ['label' => 'Sore (16:00)', 'time' => $bStart->copy()->setTime(16, 0)],
                    ['label' => 'Selesai (20:00)', 'time' => $bStart->copy()->setTime(20, 0)],
                ];

                $timeBuckets[] = [
                    'start' => $bStart,
                    'end' => $bEnd,
                    'label' => $label,
                    'short_name' => $shortName,
                    'full_name' => $fullName,
                    'sub_points' => $subPoints,
                ];
                $cursor->addDay();
            }
        } elseif ($groupingType === 'weekly') {
            $cursor = $startDate->copy();
            $weekIndex = 1;
            while ($cursor->lte($endDate)) {
                $bStart = $cursor->copy()->startOfDay();
                $bEnd = $cursor->copy()->addDays(6)->endOfDay();
                if ($bEnd->gt($endDate)) {
                    $bEnd = $endDate->copy()->endOfDay();
                }

                $label = 'Mgg ' . $weekIndex . ' (' . $bStart->format('d/m') . '-' . $bEnd->format('d/m') . ')';
                $shortName = 'M' . $weekIndex;
                $fullName = 'Minggu ' . $weekIndex . ' (' . $bStart->format('d M') . ' – ' . $bEnd->format('d M Y') . ')';

                $subPoints = [];
                $dayCur = $bStart->copy();
                while ($dayCur->lte($bEnd)) {
                    $subPoints[] = [
                        'label' => ($dayNames[$dayCur->dayOfWeek] ?? '') . ', ' . $dayCur->format('d/m'),
                        'time' => $dayCur->copy()->endOfDay(),
                    ];
                    $dayCur->addDay();
                }

                $timeBuckets[] = [
                    'start' => $bStart,
                    'end' => $bEnd,
                    'label' => $label,
                    'short_name' => $shortName,
                    'full_name' => $fullName,
                    'sub_points' => $subPoints,
                ];
                $cursor = $bEnd->copy()->addSecond()->startOfDay();
                $weekIndex++;
            }
        } else { // bulanan
            $cursor = $startDate->copy()->startOfMonth();
            while ($cursor->lte($endDate)) {
                $bStart = $cursor->copy()->startOfDay();
                if ($bStart->lt($startDate)) {
                    $bStart = $startDate->copy()->startOfDay();
                }
                $bEnd = $cursor->copy()->endOfMonth()->endOfDay();
                if ($bEnd->gt($endDate)) {
                    $bEnd = $endDate->copy()->endOfDay();
                }

                $mNum = (int) $cursor->month;
                $monthName = $monthNames[$mNum] ?? $cursor->format('F');
                $label = $monthName . ' ' . $cursor->year;
                $shortName = substr($monthName, 0, 3) . ($cursor->year !== (int) now()->year ? " '" . substr((string) $cursor->year, -2) : '');
                $fullName = $monthName . ' ' . $cursor->year;

                $subPoints = [];
                $mStart = $cursor->copy()->startOfMonth()->startOfDay();
                $mEnd = $cursor->copy()->endOfMonth()->endOfDay();
                for ($w = 1; $w <= 4; $w++) {
                    $wEnd = $mStart->copy()->addDays($w * 7 - 1)->endOfDay();
                    if ($wEnd->gt($mEnd)) {
                        $wEnd = $mEnd->copy();
                    }
                    $subPoints[] = [
                        'label' => 'Minggu ' . $w,
                        'time' => $wEnd,
                    ];
                }

                $timeBuckets[] = [
                    'start' => $bStart,
                    'end' => $bEnd,
                    'label' => $label,
                    'short_name' => $shortName,
                    'full_name' => $fullName,
                    'sub_points' => $subPoints,
                ];
                $cursor->addMonthNoOverflow()->startOfMonth();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TASK UNTUK BAR CHART & LINE CHART DRILLDOWN
        |--------------------------------------------------------------------------
        | Query langsung dari Model Task agar seluruh task masuk ke diagram batang
        | dan diagram garis (histori rincian progres).
        |--------------------------------------------------------------------------
        */

        $chartTasks = \App\Models\Task::query()
            ->when($divisionId, function ($query) use ($divisionId) {
                $query->where('division_id', $divisionId);
            })
            ->when($employeeId, function ($query) use ($employeeId) {
                $query->where('assigned_to', $employeeId);
            })
            ->with([
                'target',
                'subTarget.target',
                'division',
                'assignedTo',
                'logbooks' => function ($query) {
                    $query->orderBy('date');
                },
            ])
            ->orderBy('id')
            ->get();

        $monthlyChartTasks = [];
        $chartTaskCatalog = [];

        foreach ($chartTasks as $taskIndex => $task) {
            $taskNumber = $taskIndex + 1;
            $target = $task->target ?? $task->subTarget?->target;
            $targetId = $target?->id ?? 0;
            $targetName = $target?->title ?? 'Tanpa Target';
            $employeeName = $task->assignedTo?->name ?? 'Belum ditentukan';
            $divisionName = $task->division?->name ?? 'Tanpa Divisi';

            $chartTaskCatalog[] = [
                'task_number' => $taskNumber,
                'task_name' => $task->title,
                'division_name' => $divisionName,
                'employee_name' => $employeeName,
            ];

            $workType = strtolower(trim((string) $task->work_type));
            $taskCreated = $task->created_at ? Carbon::parse($task->created_at) : null;
            $taskLogbooks = $task->logbooks ? $task->logbooks->sortBy('date') : collect();
            $firstLogDate = $taskLogbooks->first()?->date ? Carbon::parse($taskLogbooks->first()->date) : null;
            $effectiveTaskStart = ($firstLogDate && (!$taskCreated || $firstLogDate->lt($taskCreated)))
                ? $firstLogDate
                : $taskCreated;

            foreach ($timeBuckets as $bucket) {
                $bEnd = $bucket['end'];
                $existsByBucketEnd = !$effectiveTaskStart || $effectiveTaskStart->lte($bEnd);

                if (!$existsByBucketEnd) {
                    $planning = 0.0;
                    $actual = 0.0;
                    $drilldownData = [];
                } else {
                    if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
                        $rawPlanning = 100.0;
                    } else {
                        $rawPlanning = $this->calculatePlanning($task, $bEnd);
                    }

                    // Ambil seluruh logbook task yang disetujui sampai akhir bucket (kronologis)
                    $approvedLogsUpToBucket = $taskLogbooks
                        ->where('status', 'approved')
                        ->filter(function ($lb) use ($bEnd) {
                            return $lb->date && Carbon::parse($lb->date)->lte($bEnd);
                        })
                        ->sortBy('date')
                        ->values();

                    if ($approvedLogsUpToBucket->isNotEmpty()) {
                        // Actual adalah progres dari logbook approved terakhir (misal 10,30,50,80 -> diambil 80%)
                        $latestApprovedLog = $approvedLogsUpToBucket->last();
                        $actual = (float) $latestApprovedLog->progress;
                    } else {
                        $actual = (float) ($task->verified_progress ?? 0);
                    }

                    // ATURAN: Tidak boleh planning di bawah aktual pada bagian progress dan task
                    $planning = max((float) $rawPlanning, (float) $actual);
                    $planning = round(min(max((float) $planning, 0), 100), 1);
                    $actual = round(min(max((float) $actual, 0), 100), 1);

                    // Bangun data histori progres untuk Diagram Garis (Line Chart)
                    $drilldownData = [];

                    if ($approvedLogsUpToBucket->isNotEmpty()) {
                        // 1. Titik Awal: Mulai (0%)
                        $subTargetStart = $task->subTarget?->period_start ? Carbon::parse($task->subTarget->period_start)->startOfDay() : null;
                        $firstAppDate = Carbon::parse($approvedLogsUpToBucket->first()->date);
                        $initialPointDate = $subTargetStart && $subTargetStart->lt($firstAppDate) ? $subTargetStart : $firstAppDate->copy()->subDay();

                        $initPlan = in_array($workType, ['other', 'lainnya', 'dadakan'], true)
                            ? 0.0
                            : $this->calculatePlanning($task, $initialPointDate);

                        $drilldownData[] = [
                            'week'     => 1,
                            'label'    => 'Mulai',
                            'planning' => round(min(max((float) $initPlan, 0), 100), 1),
                            'actual'   => 0.0,
                        ];

                        // 2. Titik-titik histori untuk SETIAP progres yang disetujui (10%, 30%, 50%, 80%)
                        $runningActual = 0.0;
                        foreach ($approvedLogsUpToBucket as $idx => $lb) {
                            $logDate = Carbon::parse($lb->date);
                            $dateLabel = $logDate->translatedFormat('d M') ?: $logDate->format('d/m');

                            $lbProgress = (float) $lb->progress;
                            // Progres bertambah seiring laporan yang disetujui
                            $runningActual = max($runningActual, $lbProgress);

                            $planAtDate = in_array($workType, ['other', 'lainnya', 'dadakan'], true)
                                ? 100.0
                                : $this->calculatePlanning($task, $logDate->copy()->endOfDay());

                            // ATURAN: Tidak boleh planning di bawah aktual
                            $planAtDate = max((float) $planAtDate, (float) $runningActual);

                            $drilldownData[] = [
                                'week'     => $idx + 2,
                                'label'    => $dateLabel,
                                'planning' => round(min(max((float) $planAtDate, 0), 100), 1),
                                'actual'   => round(min(max((float) $runningActual, 0), 100), 1),
                            ];
                        }

                        // 3. Titik akhir (Saat Ini / Akhir Periode) jika tanggal logbook terakhir masih sebelum akhir periode
                        $lastLogDate = Carbon::parse($approvedLogsUpToBucket->last()->date)->endOfDay();
                        if ($bEnd->gt($lastLogDate)) {
                            $finalPlan = in_array($workType, ['other', 'lainnya', 'dadakan'], true)
                                ? 100.0
                                : $this->calculatePlanning($task, $bEnd);
                            $finalPlan = max((float) $finalPlan, (float) $runningActual);

                            $drilldownData[] = [
                                'week'     => count($drilldownData) + 1,
                                'label'    => 'Saat Ini',
                                'planning' => round(min(max((float) $finalPlan, 0), 100), 1),
                                'actual'   => round(min(max((float) $runningActual, 0), 100), 1),
                            ];
                        }
                    } else {
                        // Fallback jika belum ada logbook yang disetujui
                        $bMonthStart = $bucket['start']->copy()->startOfMonth();
                        $bMonthEnd   = (clone $bMonthStart)->endOfMonth();

                        $weekRunningActual = (float) ($task->verified_progress ?? 0);
                        $weekRunningPlanning = 0.0;

                        for ($w = 1; $w <= 4; $w++) {
                            if ($w === 1) {
                                $weekEnd = (clone $bMonthStart)->addDays(6)->endOfDay();
                            } elseif ($w === 2) {
                                $weekEnd = (clone $bMonthStart)->addDays(13)->endOfDay();
                            } elseif ($w === 3) {
                                $weekEnd = (clone $bMonthStart)->addDays(20)->endOfDay();
                            } else {
                                $weekEnd = (clone $bMonthEnd);
                            }
                            if ($weekEnd->gt($bMonthEnd)) {
                                $weekEnd = (clone $bMonthEnd);
                            }

                            if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
                                $spPlan = 100.0;
                            } else {
                                $spPlan = $this->calculatePlanning($task, $weekEnd);
                            }
                            $weekRunningPlanning = max($weekRunningPlanning, (float) $spPlan);
                            // ATURAN: Tidak boleh planning di bawah aktual
                            $weekRunningPlanning = max((float) $weekRunningPlanning, (float) $weekRunningActual);

                            $drilldownData[] = [
                                'week'     => $w,
                                'label'    => 'Minggu ' . $w,
                                'planning' => round(min(max($weekRunningPlanning, 0), 100), 1),
                                'actual'   => round(min(max($weekRunningActual, 0), 100), 1),
                            ];
                        }
                    }
                }

                $lastLog = $task->logbooks->last();
                $lastActivityFormatted = $lastLog
                    ? ($lastLog->date ? Carbon::parse($lastLog->date)->format('M d, Y_ h:i A') : ($lastLog->created_at ? $lastLog->created_at->format('M d, Y_ h:i A') : '-'))
                    : ($task->updated_at ? $task->updated_at->format('M d, Y_ h:i A') : '-');

                $monthlyChartTasks[] = [
                    'task_id' => $task->id,
                    'task_number' => $taskNumber,
                    'task_name' => $task->title,
                    'target_id' => $targetId,
                    'target_name' => $targetName,
                    'sub_target_name' => $task->subTarget?->title ?? '-',
                    'scope_name' => $task->subTarget?->title ?? '-',
                    'division_id' => $task->division_id,
                    'division_name' => $divisionName,
                    'employee_id' => $task->assigned_to,
                    'employee_name' => $employeeName,
                    'status' => $task->status ?? 'assigned',
                    'created_at_formatted' => $task->created_at ? $task->created_at->format('M d, Y_ h:i A') : '-',
                    'last_activity' => $lastActivityFormatted,
                    'period_name' => $bucket['label'],
                    'period_full' => $bucket['full_name'],
                    'short_name' => $bucket['short_name'],
                    'month_name' => $bucket['short_name'],
                    'planning' => $planning,
                    'actual' => $actual,
                    'drilldown' => $drilldownData,
                    'weekly' => $drilldownData,
                ];
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TAHUN YANG TERSEDIA
        |--------------------------------------------------------------------------
        */

        $availableYears = range(
            now()->year - 4,
            now()->year + 1
        );

        $departments = Department::query()->orderBy('name')->get();

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'leader.dashboard',
            compact(
                'employeeMonitoring',
                'chartData',
                'departments',
                'divisions',
                'projects',
                'availableYears',
                'selectedYear',
                'startDate',
                'endDate',
                'preset',
                'filterMonth',
                'filterYear',
                'diffDays',
                'groupingType',
                'groupingLabel',
                'activeSection',
                'asOfDate',
                'chartRows',
                'chartEmployees',
                'targetMonitoring',
                'monthlyChartTasks',
                'chartTaskCatalog',
                'totalEmployees',
                'departmentId',
                'divisionId',
                'targetId',
                'subTargetId',
                'employeeId'
            )
        );
    }

    public function summarize(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $startDate = $filters['startDate'];
        $endDate = $filters['endDate'];
        $monitorDate = $endDate;
        $employeeId = $filters['employee_id'];

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN AI PER KARYAWAN (SPESIFIK)
        |--------------------------------------------------------------------------
        | Menghasilkan ringkasan terstruktur 4 bagian:
        | A. Progres Pekerjaan (Dihitung langsung oleh Laravel, konsisten dashboard)
        | B. Ringkasan Aktivitas (Data logbook aktual)
        | C. Kendala (Hanya kendala nyata, atau "Belum ada kendala yang dilaporkan")
        | D. Rekomendasi Tindak Lanjut (Singkat, relevan, logis)
        | Target panjang teks AI: 40-60 kata, waktu baca 10-20 detik.
        |--------------------------------------------------------------------------
        */
        if ($employeeId) {
            $employee = User::query()
                ->where('role', 'employee')
                ->where('id', $employeeId)
                ->with('divisionRelation')
                ->first();

            if (!$employee) {
                return response()->json([
                    'message' => 'Data karyawan tidak ditemukan atau Anda tidak memiliki hak akses.',
                ], 404);
            }

            // Hitung metrik progres aktual, target, dan selisih secara eksak di Laravel
            $tasks = Task::query()
                ->where('assigned_to', $employee->id)
                ->when($filters['division_id'], function ($query) use ($filters) {
                    $query->where('division_id', $filters['division_id']);
                })
                ->where(function ($query) use ($monitorDate) {
                    $query->whereNull('created_at')
                        ->orWhere('created_at', '<=', $monitorDate);
                })
                ->with([
                    'target',
                    'subTarget',
                    'logbooks' => function ($q) {
                        $q->orderBy('date');
                    }
                ])
                ->get();

            $totalPlanning = 0;
            $totalActual = 0;
            $totalWeight = 0;
            $totalTasks = 0;

            foreach ($tasks as $task) {
                $workType = strtolower(trim((string) $task->work_type));
                $planning = in_array($workType, ['other', 'lainnya', 'dadakan'], true)
                    ? 100
                    : $this->calculatePlanning($task, $monitorDate);

                $latestApprovedLog = $task->logbooks
                    ->where('status', 'approved')
                    ->filter(function ($lb) use ($endDate) {
                        return $lb->date && Carbon::parse($lb->date)->lte($endDate);
                    })
                    ->sortByDesc('date')
                    ->sortByDesc('id')
                    ->first();

                $actual = (float) ($latestApprovedLog?->progress ?? $task->verified_progress ?? 0);
                $actual = min(max($actual, 0), 100);
                $planning = max((float) $planning, (float) $actual);
                $planning = min(max((float) $planning, 0), 100);

                $weight = (float) ($task->weight ?? 0);
                if ($weight <= 0) {
                    $weight = 1;
                }

                $totalPlanning += $planning * $weight;
                $totalActual += $actual * $weight;
                $totalWeight += $weight;
                $totalTasks++;
            }

            if ($totalWeight > 0) {
                $planningVal = round($totalPlanning / $totalWeight);
                $actualVal = round($totalActual / $totalWeight);
            } else {
                $planningVal = 0;
                $actualVal = 0;
            }

            $planningVal = max((float) $planningVal, (float) $actualVal);
            $planningVal = min(max($planningVal, 0), 100);
            $actualVal = min(max($actualVal, 0), 100);
            $gapVal = $actualVal - $planningVal;

            $hasData = ($totalTasks > 0);

            if (!$hasData) {
                $progressText = 'Data progres atau target belum lengkap (Belum ada pekerjaan yang ditugaskan).';
                $gapText = 'Data belum lengkap';
                $statusText = 'Belum Ada Task';
            } else {
                if ($gapVal < 0) {
                    $gapText = abs($gapVal) . ' poin persentase di bawah target';
                    $statusText = 'Tertinggal';
                } elseif ($gapVal > 0) {
                    $gapText = $gapVal . ' poin persentase di atas target';
                    $statusText = 'Lebih Cepat';
                } else {
                    $gapText = 'Sesuai dengan target';
                    $statusText = 'Sesuai Rencana';
                }
                $progressText = "{$actualVal}% dari target {$planningVal}%. (Selisih: {$gapText})";
            }

            $taskList = $tasks->map(function ($t) use ($endDate) {
                $latestApprovedLog = $t->logbooks
                    ->where('status', 'approved')
                    ->filter(function ($lb) use ($endDate) {
                        return $lb->date && Carbon::parse($lb->date)->lte($endDate);
                    })
                    ->sortByDesc('date')
                    ->sortByDesc('id')
                    ->first();

                $act = (float) ($latestApprovedLog?->progress ?? $t->verified_progress ?? 0);
                return [
                    'id' => $t->id,
                    'title' => $t->title,
                    'actual' => (int) round(min(max($act, 0), 100)),
                ];
            })->values()->all();

            // Ambil daftar sub-target karyawan
            $subTargets = $tasks->map(function ($t) {
                return $t->subTarget?->title ?? $t->target?->title;
            })->filter(fn($v) => filled(trim((string)$v)))->unique()->values()->all();

            if (empty($subTargets)) {
                $subTargets = $tasks->pluck('title')->filter(fn($v) => filled(trim((string)$v)))->unique()->values()->all();
            }

            // Ambil logbook karyawan dalam rentang waktu terpilih
            $logbooks = Logbook::query()
                ->where('user_id', $employee->id)
                ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
                ->with(['task'])
                ->orderByDesc('date')
                ->orderByDesc('id')
                ->get();

            // Fallback jika tidak ada logbook dalam rentang waktu terpilih
            if ($logbooks->isEmpty()) {
                $logbooks = Logbook::query()
                    ->where('user_id', $employee->id)
                    ->with(['task'])
                    ->orderByDesc('date')
                    ->orderByDesc('id')
                    ->take(20)
                    ->get();
            }

            $rawActivities = $logbooks->map(fn ($lb) => trim((string) $lb->activity))
                ->filter(fn ($txt) => filled($txt))
                ->unique()
                ->values()
                ->all();

            $rawObstacles = $logbooks->map(function ($lb) {
                $obs = trim((string) $lb->obstacle);
                return in_array(strtolower($obs), ['-', 'tidak ada', 'tidak ada kendala', 'nihil', 'none', 'aman', 'lancar', ''], true) ? null : $obs;
            })->filter(fn ($obs) => filled($obs))->unique()->values()->all();

            $kegiatanList = !empty($rawActivities) ? array_slice($rawActivities, 0, 3) : ['Belum ada aktivitas yang dicatat.'];
            $kendalaList = !empty($rawObstacles) ? array_slice($rawObstacles, 0, 3) : ['Tidak ada kendala.'];

            // Cek Cache jika tidak force refresh
            $cacheKey = 'pimpinan_ai_emp_v5_' . $employee->id . '_' . md5($startDate->toDateString() . $endDate->toDateString());
            if (!$request->boolean('fresh') && Cache::has($cacheKey)) {
                return response()->json(Cache::get($cacheKey));
            }

            // Hubungi Gemini AI untuk merapikan poin kegiatan dan kendala
            $apiKey = config('services.gemini.key');
            if ($apiKey && (!empty($rawActivities) || !empty($rawObstacles))) {
                $prompt = "Anda adalah asisten AI eksekutif untuk Pimpinan perusahaan.\n"
                    . "Buat ringkasan kinerja logbook karyawan dengan format TEPAT seperti berikut tanpa teks pembuka atau penutup:\n\n"
                    . "nama : {$employee->name}\n"
                    . "divisi : " . ($employee->divisionRelation?->name ?? 'Tanpa Divisi') . "\n"
                    . "sub target :\n"
                    . (!empty($subTargets) ? implode("\n", array_map(fn($st) => "- " . $st, $subTargets)) : "- Belum ada sub target.") . "\n"
                    . "kegiatan yg dilakukan :\n"
                    . "- [poin kegiatan 1 (singkat)]\n"
                    . "- [poin kegiatan 2 (singkat)]\n"
                    . "kendala :\n"
                    . "- [poin kendala 1 (singkat)]\n"
                    . "- [poin kendala 2 (singkat)]\n\n"
                    . "Data Karyawan:\n"
                    . "- Nama: {$employee->name}\n"
                    . "- Divisi: " . ($employee->divisionRelation?->name ?? 'Tanpa Divisi') . "\n"
                    . "- Sub Target: " . implode(', ', $subTargets) . "\n"
                    . "- Catatan Aktivitas Logbook:\n" . implode("\n", array_map(fn($a) => "  • {$a}", array_slice($rawActivities, 0, 10))) . "\n"
                    . "- Catatan Kendala Logbook:\n" . (!empty($rawObstacles) ? implode("\n", array_map(fn($o) => "  • {$o}", array_slice($rawObstacles, 0, 6))) : "  • Tidak ada kendala.") . "\n\n"
                    . "ATURAN KETAT:\n"
                    . "1. Bagian 'kegiatan yg dilakukan :' HANYA berisi 2 sampai 3 poin ringkasan kegiatan utama saja (maksimal 3 poin). Setiap poin harus sangat ringkas, padat, jelas (1 kalimat pendek, jangan kepanjangan), diawali tanda strip (-).\n"
                    . "2. Bagian 'kendala :' HANYA berisi 2 sampai 3 poin kendala nyata saja (maksimal 3 poin). Buat sangat ringkas dan to-the-point (1 kalimat pendek, jangan kepanjangan). Jika tidak ada kendala tercatat, tulis persis: - Tidak ada kendala.\n"
                    . "3. Jangan menambahkan pengantar, penutup, atau format lain di luar template.";

                try {
                    $response = Http::timeout(25)
                        ->withHeaders(['x-goog-api-key' => $apiKey])
                        ->post(
                            'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
                            [
                                'contents' => [
                                    ['role' => 'user', 'parts' => [['text' => $prompt]]],
                                ],
                                'generationConfig' => [
                                    'temperature' => 0.2,
                                    'maxOutputTokens' => 600,
                                ],
                            ]
                        );

                    if ($response->successful()) {
                        $rawAiText = trim((string) $response->json('candidates.0.content.parts.0.text'));
                        if (filled($rawAiText)) {
                            // Parse kegiatan
                            if (preg_match('/kegiatan\s*(?:yg|yang)?\s*dilakukan\s*:\s*(.*?)(?=\bkendala\s*:|$)/is', $rawAiText, $mKeg)) {
                                $lines = explode("\n", trim($mKeg[1]));
                                $parsedKeg = [];
                                foreach ($lines as $line) {
                                    $l = trim($line);
                                    if (preg_match('/^[-*•]\s*(.+)$/', $l, $subM)) {
                                        $parsedKeg[] = trim($subM[1]);
                                    } elseif (filled($l)) {
                                        $parsedKeg[] = $l;
                                    }
                                }
                                if (!empty($parsedKeg)) {
                                    $kegiatanList = array_slice($parsedKeg, 0, 3);
                                }
                            }

                            // Parse kendala
                            if (preg_match('/kendala\s*:\s*(.*?)$/is', $rawAiText, $mKen)) {
                                $lines = explode("\n", trim($mKen[1]));
                                $parsedKen = [];
                                foreach ($lines as $line) {
                                    $l = trim($line);
                                    if (preg_match('/^[-*•]\s*(.+)$/', $l, $subM)) {
                                        $parsedKen[] = trim($subM[1]);
                                    } elseif (filled($l)) {
                                        $parsedKen[] = $l;
                                    }
                                }
                                if (!empty($parsedKen)) {
                                    $kendalaList = array_slice($parsedKen, 0, 3);
                                }
                            }
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Gemini AI summary exception: ' . $e->getMessage());
                }
            }

            // Batasi maksimal 3 poin dan panjang karakter per poin agar tetap ringkas dan tidak kepanjangan
            $kegiatanList = array_map(function ($k) {
                return Str::limit(trim($k), 130);
            }, array_slice($kegiatanList, 0, 3));

            $kendalaList = array_map(function ($k) {
                return Str::limit(trim($k), 130);
            }, array_slice($kendalaList, 0, 3));

            $subTargetBullets = !empty($subTargets) ? $subTargets : ['Belum ada sub target.'];
            $subTargetTextLines = [];
            foreach ($subTargetBullets as $idx => $st) {
                if ($idx === 0) {
                    $subTargetTextLines[] = "- " . $st;
                } else {
                    $subTargetTextLines[] = "             - " . $st;
                }
            }

            $finalSummary = "nama : {$employee->name}\n"
                . "divisi : " . ($employee->divisionRelation?->name ?? 'Tanpa Divisi') . "\n"
                . "sub target : " . implode("\n", $subTargetTextLines) . "\n"
                . "kegiatan yg dilakukan : \n"
                . implode("\n", array_map(fn($kg) => "- " . $kg, $kegiatanList)) . "\n\n"
                . "kendala : \n"
                . implode("\n", array_map(fn($kd) => "- " . $kd, $kendalaList));

            $result = [
                'employee' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'division' => $employee->divisionRelation?->name ?? 'Tanpa Divisi',
                ],
                'progress' => [
                    'has_data' => $hasData,
                    'actual' => $actualVal,
                    'target' => $planningVal,
                    'gap' => $gapVal,
                    'gap_text' => $gapText,
                    'status' => $statusText,
                    'task_count' => $totalTasks,
                    'progress_text' => $progressText,
                ],
                'tasks' => $taskList,
                'report' => [
                    'nama' => $employee->name,
                    'divisi' => $employee->divisionRelation?->name ?? 'Tanpa Divisi',
                    'sub_targets' => !empty($subTargets) ? $subTargets : ['Belum ada sub target.'],
                    'kegiatan' => $kegiatanList,
                    'kendala' => $kendalaList,
                ],
                'summary' => $finalSummary,
                'source' => [
                    'total_tasks' => $totalTasks,
                    'total_logbooks' => $logbooks->count(),
                    'period' => $startDate->format('d M Y') . ' - ' . $endDate->format('d M Y'),
                ],
            ];

            Cache::put($cacheKey, $result, now()->addMinutes(15));

            return response()->json($result);
        }

        /*
        |--------------------------------------------------------------------------
        | RINGKASAN AI GLOBAL (FALLBACK JIKA TANPA EMPLOYEE ID)
        |--------------------------------------------------------------------------
        */
        $logs = Logbook::query()
            ->whereNotNull('task_id')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->whereHas('user', function ($query) use ($filters) {
                $query->where('role', 'employee')
                    ->when($filters['division_id'], function ($query) use ($filters) {
                        $query->where('division_id', $filters['division_id']);
                    });
            })
            ->with([
                'user.divisionRelation',
                'task.target',
                'task.subTarget.target',
            ])
            ->latest('date')
            ->latest('id')
            ->get()
            ->unique(fn ($logbook) => $logbook->user_id . ':' . $logbook->task_id)
            ->take(50)
            ->map(function ($logbook) {
                $task = $logbook->task;
                $target = $task?->target ?? $task?->subTarget?->target;

                return [
                    'karyawan' => $logbook->user?->name ?? 'Karyawan',
                    'divisi' => $logbook->user?->divisionRelation?->name ?? 'Tanpa Divisi',
                    'pekerjaan' => $task?->title ?? 'Pekerjaan',
                    'target' => $target?->title ?? 'Target tidak tersedia',
                    'progress_laporan' => (float) ($logbook->progress ?? 0),
                    'progress_terverifikasi' => (float) ($task?->verified_progress ?? 0),
                    'progress_target' => (float) ($task?->target_progress ?? 100),
                    'kendala' => filled(trim((string) $logbook->obstacle))
                        ? trim($logbook->obstacle)
                        : 'Tidak dicatat',
                    'tanggal' => $logbook->date?->format('Y-m-d'),
                ];
            })
            ->values();

        if ($logs->isEmpty()) {
            return response()->json([
                'message' => 'Tidak ada logbook maupun pekerjaan karyawan pada filter yang dipilih.',
            ], 422);
        }

        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            return response()->json([
                'message' => 'Layanan ringkasan belum dikonfigurasi. Periksa GEMINI_API_KEY.',
            ], 503);
        }

        $prompt = "Buat ringkasan monitoring pekerjaan dalam Bahasa Indonesia yang sangat ringkas, padat, dan profesional untuk Pimpinan. "
            . "PENTING: Buat ringkas 2-3 poin saja untuk kegiatan/pekerjaan utama dan kendalanya, jangan kepanjangan. "
            . "Gunakan hanya fakta pada data JSON berikut. Untuk setiap pekerjaan atau karyawan, sebutkan capaian persentase dari target yang diminta (contoh: 'Progres 65% dari 100% yang diminta'). "
            . "Sebutkan kendala jika ada (maksimal 2-3 kendala ringkas), serta poin tindak lanjut singkat. "
            . "Format menggunakan bullet point pendek yang rapi. Data monitoring: "
            . json_encode($logs, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        try {
            $response = Http::timeout(30)
                ->withHeaders(['x-goog-api-key' => $apiKey])
                ->post(
                    'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
                    [
                        'contents' => [
                            ['role' => 'user', 'parts' => [['text' => $prompt]]],
                        ],
                        'generationConfig' => [
                            'temperature' => 0.2,
                            'maxOutputTokens' => 600,
                        ],
                    ]
                );

            if ($response->successful()) {
                $summary = $response->json('candidates.0.content.parts.0.text');
                return response()->json([
                    'summary' => trim((string) $summary),
                    'records' => $logs->count(),
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Gemini global summary exception: ' . $e->getMessage());
        }

        return response()->json([
            'message' => 'Gemini tidak dapat memproses ringkasan saat ini. Coba lagi beberapa saat lagi.',
        ], 502);
    }

    private function validatedFilters(Request $request): array
    {
        $availableYears = range(now()->year - 4, now()->year + 1);

        $validated = $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'preset' => ['nullable', 'string', Rule::in(['7_days', '30_days', 'this_month', 'last_month', 'this_year', 'month_picker', 'custom'])],
            'filter_month' => ['nullable', 'integer', 'between:1,12'],
            'filter_year' => ['nullable', 'integer'],
            'department_id' => ['nullable', 'integer', Rule::exists('departments', 'id')],
            'division_id' => ['nullable', 'integer', Rule::exists('divisions', 'id')],
            'target_id' => ['nullable', 'integer', Rule::exists('targets', 'id')],
            'sub_target_id' => ['nullable', 'integer', Rule::exists('sub_targets', 'id')],
            'employee_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($request) {
                    $query->where('role', 'employee');

                    if ($request->filled('division_id')) {
                        $query->where('division_id', $request->input('division_id'));
                    }
                }),
            ],
            'section' => ['sometimes', Rule::in(['dashboard', 'monitoring'])],
            'year' => ['sometimes', 'integer'],
            'months' => ['sometimes', 'array'],
            'months.*' => ['integer', 'between:1,12'],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal akhir tidak valid.',
        ]);

        $preset = $request->query('preset') ?: $request->input('preset');
        $startDateInput = $request->query('start_date') ?: $request->input('start_date');
        $endDateInput = $request->query('end_date') ?: $request->input('end_date');
        $filterMonth = $request->query('filter_month') ?: $request->input('filter_month');
        $filterYear = $request->query('filter_year') ?: $request->input('filter_year');

        if ($preset === '7_days') {
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($preset === '30_days') {
            $startDate = now()->subDays(29)->startOfDay();
            $endDate = now()->endOfDay();
        } elseif ($preset === 'last_month') {
            $startDate = now()->subMonthNoOverflow()->startOfMonth()->startOfDay();
            $endDate = now()->subMonthNoOverflow()->endOfMonth()->endOfDay();
        } elseif ($preset === 'this_year') {
            $startDate = now()->startOfYear()->startOfDay();
            $endDate = now()->endOfYear()->endOfDay();
        } elseif ($preset === 'this_month') {
            $startDate = now()->startOfMonth()->startOfDay();
            $endDate = now()->endOfMonth()->endOfDay();
        } elseif ($preset === 'month_picker' && $filterMonth && $filterYear) {
            $startDate = Carbon::create((int) $filterYear, (int) $filterMonth, 1)->startOfDay();
            $endDate = Carbon::create((int) $filterYear, (int) $filterMonth, 1)->endOfMonth()->endOfDay();
        } elseif ($startDateInput && $endDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
            $endDate = Carbon::parse($endDateInput)->endOfDay();
            if ($startDate->gt($endDate)) {
                $endDate = $startDate->copy()->endOfDay();
            }
            $preset = $preset ?: 'custom';
        } else {
            if ($request->filled('year')) {
                $yr = (int) $request->input('year');
                $mths = (array) $request->input('months', [(int) now()->month]);
                $minM = !empty($mths) ? min($mths) : (int) now()->month;
                $maxM = !empty($mths) ? max($mths) : (int) now()->month;
                $startDate = Carbon::create($yr, $minM, 1)->startOfDay();
                $endDate = Carbon::create($yr, $maxM, 1)->endOfMonth()->endOfDay();
                $preset = 'custom';
            } else {
                $preset = 'this_month';
                $startDate = now()->startOfMonth()->startOfDay();
                $endDate = now()->endOfMonth()->endOfDay();
            }
        }

        $filterMonth = (int) ($filterMonth ?: $startDate->month);
        $filterYear = (int) ($filterYear ?: $startDate->year);
        $diffDays = (int) ($startDate->diffInDays($endDate) + 1);

        if ($diffDays <= 14) {
            $groupingType = 'daily';
            $groupingLabel = 'Harian (' . $diffDays . ' Hari)';
        } elseif ($diffDays <= 60) {
            $groupingType = 'weekly';
            $groupingLabel = 'Mingguan (' . $diffDays . ' Hari)';
        } else {
            $groupingType = 'bulanan';
            $groupingLabel = 'Bulanan (' . $diffDays . ' Hari)';
        }

        $monitorDate = $endDate->copy()->endOfDay();
        $asOfDate = $endDate->toDateString();

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'preset' => $preset,
            'filterMonth' => $filterMonth,
            'filterYear' => $filterYear,
            'diffDays' => $diffDays,
            'groupingType' => $groupingType,
            'groupingLabel' => $groupingLabel,
            'monitorDate' => $monitorDate,
            'asOfDate' => $asOfDate,
            'year' => (int) $startDate->year,
            'department_id' => isset($validated['department_id']) ? (int) $validated['department_id'] : null,
            'division_id' => isset($validated['division_id']) ? (int) $validated['division_id'] : null,
            'target_id' => isset($validated['target_id']) ? (int) $validated['target_id'] : null,
            'sub_target_id' => isset($validated['sub_target_id']) ? (int) $validated['sub_target_id'] : null,
            'employee_id' => isset($validated['employee_id']) ? (int) $validated['employee_id'] : null,
            'section' => $validated['section'] ?? 'dashboard',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | HITUNG PLANNING
    |--------------------------------------------------------------------------
    */

    private function calculatePlanning(
        $task,
        Carbon $monitorDate
    ): float {

        /*
        |--------------------------------------------------------------------------
        | TIDAK ADA SUB TARGET
        |--------------------------------------------------------------------------
        */

        if (!$task->subTarget) {

            return (float) (
                $task->target_progress ?? 0
            );
        }

        $subTarget = $task->subTarget;

        /*
        |--------------------------------------------------------------------------
        | TANGGAL SUB TARGET TIDAK LENGKAP
        |--------------------------------------------------------------------------
        */

        if (
            !$subTarget->period_start ||
            !$subTarget->period_end
        ) {

            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | PERIODE SUB TARGET
        |--------------------------------------------------------------------------
        */

        $startDate = Carbon::parse(
            $subTarget->period_start
        )->startOfDay();

        $endDate = Carbon::parse(
            $subTarget->period_end
        )->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | SEBELUM SUB TARGET DIMULAI
        |--------------------------------------------------------------------------
        */

        if ($monitorDate->lt($startDate)) {

            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | SETELAH SUB TARGET SELESAI
        |--------------------------------------------------------------------------
        */

        if ($monitorDate->gte($endDate)) {

            return 100;
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG TOTAL HARI
        |--------------------------------------------------------------------------
        */

        $totalDays = $startDate->diffInDays(
            $endDate
        );

        /*
        |--------------------------------------------------------------------------
        | HITUNG HARI YANG SUDAH BERJALAN
        |--------------------------------------------------------------------------
        */

        $elapsedDays = $startDate->diffInDays(
            $monitorDate
        );

        /*
        |--------------------------------------------------------------------------
        | CEK PERIODE
        |--------------------------------------------------------------------------
        */

        if ($totalDays <= 0) {

            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | HITUNG PLANNING
        |--------------------------------------------------------------------------
        */

        $planning = (
            $elapsedDays /
            $totalDays
        ) * 100;

        /*
        |--------------------------------------------------------------------------
        | BATAS PLANNING 0 - 100
        |--------------------------------------------------------------------------
        */

        return round(
            min(
                max(
                    $planning,
                    0
                ),
                100
            )
        );
    }
}