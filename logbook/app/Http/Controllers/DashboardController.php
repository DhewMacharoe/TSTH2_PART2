<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Division;
use App\Models\Logbook;
use App\Models\Task;
use App\Models\User;
use App\Models\Target;
use App\Models\SubTarget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $user = auth()->user();

        if (($user->status ?? 'approved') === 'pending') {
            return redirect()->route('approval.pending');
        }

        $hasRole = function ($roles) use ($user) {
            $roles = (array) $roles;
            if (method_exists($user, 'hasAnyRole')) {
                try {
                    if ($user->hasAnyRole($roles)) return true;
                } catch (\Throwable $e) {}
            }
            return in_array($user->role ?? '', $roles);
        };

        if ($hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($hasRole('kepala_departemen')) {
            return redirect()->route('kepala_departemen.dashboard');
        }

        if ($hasRole('kepala_divisi')) {
            return redirect()->route('kepala_divisi.dashboard');
        }

        if ($hasRole(['employee', 'karyawan', 'leader', 'agronom'])) {
            return redirect()->route('employee.dashboard');
        }

        if ($hasRole('pimpinan')) {
            return redirect()->route('pimpinan.dashboard');
        }

        $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
        $urlPengunjung = rtrim(config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')), '/');

        if ($hasRole(['pic_gudang', 'pic_lab', 'warehouse'])) {
            return redirect()->away($urlGudang . '/dasbor');
        }

        if ($hasRole('web_admin')) {
            return redirect()->away($urlPengunjung . '/web-management/home');
        }

        abort(403);
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */

    public function admin()
    {
        $totalUsers = User::count();

        $totalEmployees = User::where('role', 'employee')->count();

        $totalLeaders = User::where('role', 'leader')->count();

        $totalSupervisors = User::where('role', 'supervisor')->count();

        $totalLogbooks = Logbook::count();

        return view(
            'admin.dashboard',
            compact(
                'totalUsers',
                'totalEmployees',
                'totalLeaders',
                'totalSupervisors',
                'totalLogbooks'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KARYAWAN
    |--------------------------------------------------------------------------
    */

        public function employee()
        {
            $userId = auth()->id();

            $totalLogbooks = Logbook::where(
                'user_id',
                $userId
            )->count();

            $pendingLogbooks = Logbook::where(
                'user_id',
                $userId
            )
                ->where('status', 'pending')
                ->count();

            $approvedLogbooks = Logbook::where(
                'user_id',
                $userId
            )
                ->where('status', 'approved')
                ->count();

            $latestLogbooks = Logbook::where(
                'user_id',
                $userId
            )
                ->latest('date')
                ->latest('id')
                ->take(5)
                ->get();

            return view(
                'employee.dashboard',
                compact(
                    'totalLogbooks',
                    'pendingLogbooks',
                    'approvedLogbooks',
                    'latestLogbooks'
                )
            );
        }

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PIMPINAN
    |--------------------------------------------------------------------------
    */

    public function leader()
    {
        /*
        |--------------------------------------------------------------------------
        | RINGKASAN LOGBOOK
        |--------------------------------------------------------------------------
        */

        $totalLogbooks = Logbook::count();

        $approvedLogbooks = Logbook::where(
            'status',
            'approved'
        )->count();

        $rejectedLogbooks = Logbook::where(
            'status',
            'rejected'
        )->count();

        $reviewedLogbooks =
            $approvedLogbooks +
            $rejectedLogbooks;

        $reviewRate = $totalLogbooks > 0
            ? round(
                ($reviewedLogbooks / $totalLogbooks) * 100
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK HARI INI
        |--------------------------------------------------------------------------
        */

        $todayLogbooks = Logbook::whereDate(
            'date',
            today()
        )->count();


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK PENDING
        |--------------------------------------------------------------------------
        */

        $pendingLogbooks = Logbook::where(
            'status',
            'pending'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | LOGBOOK DENGAN KENDALA
        |--------------------------------------------------------------------------
        */

        $obstacleLogbooks = Logbook::whereNotNull(
            'obstacle'
        )
            ->where('obstacle', '!=', '')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | PROGRESS BERDASARKAN DIVISI
        |--------------------------------------------------------------------------
        */

        $divisionProgress = DB::table('logbooks')
            ->join(
                'users',
                'logbooks.user_id',
                '=',
                'users.id'
            )
            ->select(
                'users.division',
                DB::raw(
                    'AVG(logbooks.progress) as avg_progress'
                )
            )
            ->whereNotNull('users.division')
            ->where('users.division', '!=', '')
            ->where('logbooks.status', 'approved')
            ->groupBy('users.division')
            ->get()
            ->pluck(
                'avg_progress',
                'division'
            )
            ->map(
                fn ($value) => round(
                    (float) $value,
                    1
                )
            );


        /*
        |--------------------------------------------------------------------------
        | JUMLAH LOGBOOK BERDASARKAN STATUS
        |--------------------------------------------------------------------------
        */

        $statusCounts = Logbook::select(
            'status',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('status')
            ->get()
            ->pluck(
                'count',
                'status'
            );


        /*
        |--------------------------------------------------------------------------
        | RATA-RATA PROGRESS KARYAWAN
        |--------------------------------------------------------------------------
        */

        $employeesWithAvg = User::where(
            'role',
            'employee'
        )
            ->withAvg(
                [
                    'logbooks as logbooks_avg_progress' => function ($query) {
                        $query->where(
                            'status',
                            'approved'
                        );
                    }
                ],
                'progress'
            )
            ->get()
            ->filter(
                function ($user) {
                    return $user->logbooks_avg_progress !== null;
                }
            )
            ->map(
                function ($user) {
                    $user->logbooks_avg_progress = round(
                        (float) $user->logbooks_avg_progress,
                        2
                    );

                    return $user;
                }
            );


        /*
        |--------------------------------------------------------------------------
        | CAPAIAN TINGGI
        |--------------------------------------------------------------------------
        */

        $highPerformanceEmployees = $employeesWithAvg
            ->filter(
                function ($user) {
                    return $user->logbooks_avg_progress >= 85;
                }
            )
            ->sortByDesc(
                'logbooks_avg_progress'
            )
            ->take(10)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | CAPAIAN BAIK
        |--------------------------------------------------------------------------
        */

        $goodPerformanceEmployees = $employeesWithAvg
            ->filter(
                function ($user) {
                    return
                        $user->logbooks_avg_progress >= 60 &&
                        $user->logbooks_avg_progress < 85;
                }
            )
            ->sortByDesc(
                'logbooks_avg_progress'
            )
            ->take(10)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | PERLU EVALUASI
        |--------------------------------------------------------------------------
        */

        $attentionEmployees = $employeesWithAvg
            ->filter(
                function ($user) {
                    return $user->logbooks_avg_progress < 60;
                }
            )
            ->sortBy(
                'logbooks_avg_progress'
            )
            ->take(10)
            ->values();


        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE DASHBOARD PIMPINAN
        |--------------------------------------------------------------------------
        */

        return view(
            'leader.dashboard',
            compact(
                'totalLogbooks',
                'approvedLogbooks',
                'rejectedLogbooks',
                'reviewRate',
                'todayLogbooks',
                'pendingLogbooks',
                'obstacleLogbooks',
                'divisionProgress',
                'statusCounts',
                'highPerformanceEmployees',
                'goodPerformanceEmployees',
                'attentionEmployees'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KEPALA DEPARTEMEN
    |--------------------------------------------------------------------------
    */

public function kepalaDepartemen(Request $request)
{
    $user = auth()->user();

    /*
    |--------------------------------------------------------------------------
    | DATA DEPARTEMEN
    |--------------------------------------------------------------------------
    */

    $department = Department::with([
        'targets' => function ($query) {
            $query
                ->where('status', 'active')
                ->with([
                    'subTargets',
                ])
                ->latest();
        },
        'divisions',
    ])->findOrFail($user->department_id);

    $departmentDivisionIds = $department->divisions->modelKeys();

    $request->validate([
        'start_date' => ['nullable', 'date'],
        'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        'preset' => ['nullable', 'string', Rule::in(['7_days', '30_days', 'this_month', 'last_month', 'this_year', 'month_picker', 'custom'])],
        'filter_month' => ['nullable', 'integer', 'between:1,12'],
        'filter_year' => ['nullable', 'integer'],
        'division_id' => [
            'nullable',
            'integer',
            Rule::exists('divisions', 'id')
                ->where(fn ($query) => $query->where('department_id', $department->id)),
        ],
        'target_id' => [
            'nullable',
            'integer',
            Rule::exists('targets', 'id')
                ->where(fn ($query) => $query->where('department_id', $department->id)),
        ],
        'sub_target_id' => [
            'nullable',
            'integer',
            Rule::exists('sub_targets', 'id'),
        ],
        'employee_id' => [
            'nullable',
            'integer',
            Rule::exists('users', 'id')->where(function ($query) use (
                $department,
                $request,
                $departmentDivisionIds
            ) {
                $query
                    ->where('department_id', $department->id)
                    ->where('role', 'employee')
                    ->whereIn('division_id', $departmentDivisionIds);

                if ($request->filled('division_id')) {
                    $query->where('division_id', $request->query('division_id'));
                }
            }),
        ],
    ], [
        'end_date.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.',
        'start_date.date' => 'Format tanggal mulai tidak valid.',
        'end_date.date' => 'Format tanggal akhir tidak valid.',
    ]);

    $selectedChartDivisionId = $request->filled('division_id')
        ? (int) $request->query('division_id')
        : null;

    $selectedChartTargetId = $request->filled('target_id')
        ? (int) $request->query('target_id')
        : null;

    $selectedChartSubTargetId = $request->filled('sub_target_id')
        ? (int) $request->query('sub_target_id')
        : null;

    $selectedChartEmployeeId = $request->filled('employee_id')
        ? (int) $request->query('employee_id')
        : null;

    $preset = $request->query('preset');
    $startDateInput = $request->query('start_date');
    $endDateInput = $request->query('end_date');
    $filterMonth = $request->query('filter_month');
    $filterYear = $request->query('filter_year');

    // Tentukan rentang waktu berdasarkan preset atau input tanggal manual (sistem BRImo)
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
        $startDate = \Carbon\Carbon::create((int) $filterYear, (int) $filterMonth, 1)->startOfDay();
        $endDate = \Carbon\Carbon::create((int) $filterYear, (int) $filterMonth, 1)->endOfMonth()->endOfDay();
    } elseif ($startDateInput && $endDateInput) {
        $startDate = \Carbon\Carbon::parse($startDateInput)->startOfDay();
        $endDate = \Carbon\Carbon::parse($endDateInput)->endOfDay();
        if ($startDate->gt($endDate)) {
            $endDate = $startDate->copy()->endOfDay();
        }
        $preset = $preset ?: 'custom';
    } else {
        $preset = 'this_month';
        $startDate = now()->startOfMonth()->startOfDay();
        $endDate = now()->endOfMonth()->endOfDay();
    }

    $filterMonth = (int) ($filterMonth ?: $startDate->month);
    $filterYear = (int) ($filterYear ?: $startDate->year);
    $diffDays = (int) ($startDate->diffInDays($endDate) + 1);


    /*
    |--------------------------------------------------------------------------
    | JUMLAH KARYAWAN
    |--------------------------------------------------------------------------
    */

    $totalEmployees = User::where(
        'department_id',
        $department->id
    )
        ->where('role', 'employee')
        ->count();




    /*
    |--------------------------------------------------------------------------
    | JUMLAH KEPALA DIVISI
    |--------------------------------------------------------------------------
    */

    $totalKepalaDivisi = User::where(
        'department_id',
        $department->id
    )
        ->where('role', 'kepala_divisi')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | JUMLAH DIVISI
    |--------------------------------------------------------------------------
    */

    $totalDivisions = $department->divisions->count();


    /*
    |--------------------------------------------------------------------------
    | JUMLAH TARGET AKTIF
    |--------------------------------------------------------------------------
    */

    $totalTargets = $department->targets->count();

    /*
    |--------------------------------------------------------------------------
    | TOTAL LOGBOOK SELURUH DEPARTEMEN (UNTUK PANEL DETAIL STATISTIK)
    |--------------------------------------------------------------------------
    */
    $allDepartmentLogbooks = Logbook::with([
        'user.divisionRelation',
        'task.target',
        'task.subTarget',
        'task.division',
    ])
        ->whereHas('user', function ($query) use ($department) {
            $query->where('department_id', $department->id);
        })
        ->latest('date')
        ->latest('id')
        ->get();

    $totalLogbooks = $allDepartmentLogbooks->count();

    /*
    |--------------------------------------------------------------------------
    | JUMLAH SUB TARGET
    |--------------------------------------------------------------------------
    */

    $totalSubTargets = SubTarget::whereHas(
        'target',
        function ($query) use ($department) {
            $query->where(
                'department_id',
                $department->id
            );
        }
    )->count();


    /*
    |--------------------------------------------------------------------------
    | DATA DETAIL UNTUK KOTAK STATISTIK
    |--------------------------------------------------------------------------
    */

    // Daftar karyawan dalam divisi
    $employees = User::where('department_id', $department->id)
        ->where('role', 'employee')
        ->with('divisionRelation')
        ->orderBy('name')
        ->get();


// Ambil seluruh pekerjaan yang terkait dengan departemen ini
$allTasks = Task::with(['target', 'assignedTo', 'division'])
    ->where('department_id', $department->id)
    ->latest()
    ->get();

// Hitung total berdasarkan daftar yang sama
$totalTasks = $allTasks->count();

// Pekerjaan berstatus ditugaskan
$assignedTaskList = $allTasks
    ->where('status', 'assigned')
    ->values();

// Pekerjaan sedang berjalan
$inProgressTaskList = $allTasks
    ->where('status', 'in_progress')
    ->values();

// Pekerjaan revisi
$revisionTaskList = $allTasks
    ->where('status', 'revision')
    ->values();

    /*
    |--------------------------------------------------------------------------
    | MONITORING LOGBOOK
    |--------------------------------------------------------------------------
    |
    | Kepala Departemen hanya melihat perkembangan pekerjaan
    | dan tidak melakukan approve/reject.
    |
    */


    $logbookMonitoring = Logbook::with([
        'user',
        'task.target',
        'task.subTarget',
        'task.division',
    ])
        ->whereHas('user', function ($query) use ($department, $selectedChartDivisionId) {
            $query->where('department_id', $department->id);
            if ($selectedChartDivisionId) {
                $query->where('division_id', $selectedChartDivisionId);
            }
        })
        ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
        ->when($selectedChartEmployeeId, function ($query) use ($selectedChartEmployeeId) {
            $query->where('user_id', $selectedChartEmployeeId);
        })
        ->latest('date')
        ->latest('id')
        ->get()
        ->map(function ($logbook) {
            $task = $logbook->task;

            // Bedakan logbook yang terkait dengan pekerjaan dan logbook mandiri.
            $type = $logbook->logbook_type
                ?? ($task ? 'assigned' : 'independent');

            $logbook->logbook_type_label = $type === 'independent'
                ? 'Logbook Mandiri'
                : 'Pekerjaan Ditugaskan';

            // Progress tugas hanya dibandingkan jika logbook memiliki tugas.
            $logbook->is_on_target = $task
                ? $logbook->progress >= ($task->verified_progress ?? 0)
                : null;

            $logbook->monitoring_status = !$task
                ? 'Logbook Mandiri'
                : ($logbook->is_on_target
                    ? 'Sesuai Progress'
                    : 'Dalam Proses');

            return $logbook;
        });



    /*
    |--------------------------------------------------------------------------
    | KARYAWAN PER DIVISI
    |--------------------------------------------------------------------------
    */

    $divisionEmployees = User::select(
        'division_id',
        DB::raw('COUNT(*) as total')
    )
        ->where(
            'department_id',
            $department->id
        )
        ->where(
            'role',
            'employee'
        )
        ->whereNotNull('division_id')
        ->groupBy('division_id')
        ->with('divisionRelation')
        ->get();


    /*
    |--------------------------------------------------------------------------
    | KEPALA DIVISI
    |--------------------------------------------------------------------------
    */

    $divisionHeads = User::with(
        'divisionRelation'
    )
        ->where(
            'department_id',
            $department->id
        )
        ->where(
            'role',
            'kepala_divisi'
        )
        ->whereNotNull('division_id')
        ->orderBy('name')
        ->get();

/*
|--------------------------------------------------------------------------
| PROGRESS PEKERJAAN PER DIVISI
|--------------------------------------------------------------------------
|
| Hitung rata-rata verified_progress seluruh Task
| dalam setiap divisi pada departemen ini.
|
*/

$divisionTaskProgress = Task::select(
    'division_id',
    DB::raw('COUNT(*) as total_tasks'),
    DB::raw('AVG(verified_progress) as average_progress')
)
    ->where('department_id', $department->id)
    ->whereNotNull('division_id')
    ->groupBy('division_id')
    ->with('division')
    ->get()
    ->map(function ($item) {
        $item->progress = round(
            (float) $item->average_progress,
            1
        );

        return $item;
    });


    /*
    |--------------------------------------------------------------------------
    | PEKERJAAN BERDASARKAN TARGET
    |--------------------------------------------------------------------------
    |
    | Target memiliki beberapa Sub Target.
    |
    | Bobot berasal dari Sub Target:
    |
    | Progress Sub Target × Bobot Sub Target
    | ---------------------------------------
    |                 100
    |
    */

    $targetTaskMonitoring = $department
        ->targets()
        ->with([
            'subTargets.tasks' => function ($query) use ($selectedChartDivisionId, $selectedChartEmployeeId) {
                $query->with([
                    'division',
                    'assignedTo',
                ])
                    ->when($selectedChartDivisionId, function ($q) use ($selectedChartDivisionId) {
                        $q->where('division_id', $selectedChartDivisionId);
                    })
                    ->when($selectedChartEmployeeId, function ($q) use ($selectedChartEmployeeId) {
                        $q->where('assigned_to', $selectedChartEmployeeId);
                    })
                    ->orderBy('title');
            },
        ])
        ->where('status', 'active')
        ->get()
        ->map(function ($target) {

            /*
            |--------------------------------------------------------------------------
            | HITUNG PROGRESS TARGET BERDASARKAN SUB TARGET
            |--------------------------------------------------------------------------
            */

            $weightedProgress = 0;

            foreach ($target->subTargets as $subTarget) {

                $tasks = $subTarget->tasks;

                /*
                | Progress Sub Target berasal dari rata-rata
                | progress pekerjaan yang berada di dalamnya.
                */

                $subTargetProgress = $tasks->isNotEmpty()
                    ? $tasks->avg('verified_progress')
                    : 0;

                /*
                | Kontribusi Sub Target terhadap Target.
                */

                $weightedProgress +=
                    ($subTargetProgress * $subTarget->percentage) / 100;
            }

            $target->actual_progress = round(
                $weightedProgress,
                1
            );

            /*
            |--------------------------------------------------------------------------
            | TOTAL TASK
            |--------------------------------------------------------------------------
            */

            $target->total_tasks = $target
                ->subTargets
                ->sum(function ($subTarget) {
                    return $subTarget->tasks->count();
                });

            return $target;
        });


    /*
    |--------------------------------------------------------------------------
    | DATA CHART PER BULAN PER TASK
    |--------------------------------------------------------------------------
    |
    | Untuk setiap task, hitung planning (berdasarkan periode SubTarget)
    | dan aktual (verified_progress, bersifat monoton naik) per bulan.
    |
    | Data dikirim sebagai JSON ke blade untuk grafik barchart interaktif.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | DATA CHART MONITORING BERDASARKAN RENTANG WAKTU (SISTEM BRIMO)
    |--------------------------------------------------------------------------
    |
    | Grafik otomatis menyesuaikan pengelompokan waktu:
    | - Periode pendek (<= 14 hari): Harian (Daily)
    | - Periode menengah (15 - 60 hari): Mingguan (Weekly)
    | - Periode panjang (> 60 hari): Bulanan (Monthly)
    |
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

    // Bangun time buckets sesuai pengelompokan waktu
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
    } else { // bulanan (monthly)
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

    $chartEmployees = User::query()
        ->where('department_id', $department->id)
        ->where('role', 'employee')
        ->whereIn('division_id', $departmentDivisionIds)
        ->orderBy('name')
        ->get(['id', 'name', 'division_id']);

    // Query task grafik dibatasi pada departemen, divisi, dan karyawan terpilih
    $chartTasks = Task::query()
        ->where('department_id', $department->id)
        ->when($selectedChartDivisionId, function ($query) use ($selectedChartDivisionId) {
            $query->where('division_id', $selectedChartDivisionId);
        })
        ->when($selectedChartTargetId, function ($query) use ($selectedChartTargetId) {
            $query->where('target_id', $selectedChartTargetId);
        })
        ->when($selectedChartSubTargetId, function ($query) use ($selectedChartSubTargetId) {
            $query->where('sub_target_id', $selectedChartSubTargetId);
        })
        ->when($selectedChartEmployeeId, function ($query) use ($selectedChartEmployeeId) {
            $query->where('assigned_to', $selectedChartEmployeeId);
        })
        ->with([
            'subTarget',
            'target',
            'assignedTo',
            'division',
            'logbooks' => function ($query) {
                $query->orderBy('date');
            }
        ])
        ->orderBy('title')
        ->orderBy('id')
        ->get();

    /*
     * Helper: hitung planning sesuai proporsi waktu pada tanggal tertentu
     */
    $calcPlanningAt = function ($task, \Carbon\Carbon $asOf): float {
        $subTarget = $task->subTarget;

        if (!$subTarget || !$subTarget->period_start || !$subTarget->period_end) {
            $workType = strtolower(trim((string) $task->work_type));
            if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
                return $task->created_at && \Carbon\Carbon::parse($task->created_at)->lte($asOf) ? 100.0 : 0.0;
            }
            return (float) ($task->target_progress ?? 0);
        }

        $workType = strtolower(trim((string) $task->work_type));
        if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
            return $task->created_at && \Carbon\Carbon::parse($task->created_at)->lte($asOf) ? 100.0 : 0.0;
        }

        $start = \Carbon\Carbon::parse($subTarget->period_start)->startOfDay();
        $end   = \Carbon\Carbon::parse($subTarget->period_end)->endOfDay();

        if ($asOf->lt($start)) return 0.0;
        if ($asOf->gte($end))  return 100.0;

        $totalDays   = max(1, $start->diffInDays($end));
        $elapsedDays = $start->diffInDays($asOf);

        return round(min(max(($elapsedDays / $totalDays) * 100, 0), 100), 2);
    };

    /*
     * Bangun data per task per time bucket
     */
    $monthlyChartTasks = [];
    $chartTaskCatalog = [];

    foreach ($chartTasks as $taskIndex => $task) {
        $taskNumber = $taskIndex + 1;
        $chartTaskCatalog[] = [
            'task_number' => $taskNumber,
            'task_name' => $task->title,
            'division_name' => $task->division?->name ?? 'Tanpa Divisi',
            'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
        ];

        $taskCreated = $task->created_at
            ? \Carbon\Carbon::parse($task->created_at)
            : null;

        $taskLogbooks = $task->logbooks ? $task->logbooks->sortBy('date') : collect();
        $firstLogDate = $taskLogbooks->first()?->date ? \Carbon\Carbon::parse($taskLogbooks->first()->date) : null;
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
                $rawPlanning = $calcPlanningAt($task, $bEnd);

                // Ambil seluruh logbook task yang disetujui sampai akhir bucket (diurutkan kronologis)
                $approvedLogsUpToBucket = $taskLogbooks
                    ->where('status', 'approved')
                    ->filter(function ($lb) use ($bEnd) {
                        return $lb->date && \Carbon\Carbon::parse($lb->date)->lte($bEnd);
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
                    $subTargetStart = $task->subTarget?->period_start ? \Carbon\Carbon::parse($task->subTarget->period_start)->startOfDay() : null;
                    $firstAppDate = \Carbon\Carbon::parse($approvedLogsUpToBucket->first()->date);
                    $initialPointDate = $subTargetStart && $subTargetStart->lt($firstAppDate) ? $subTargetStart : $firstAppDate->copy()->subDay();

                    $initPlan = $calcPlanningAt($task, $initialPointDate);
                    $drilldownData[] = [
                        'week'     => 1,
                        'label'    => 'Mulai',
                        'planning' => round(min(max((float) $initPlan, 0), 100), 1),
                        'actual'   => 0.0,
                    ];

                    // 2. Titik-titik histori untuk SETIAP progres yang disetujui (misal 10%, 30%, 50%, 80%)
                    $runningActual = 0.0;
                    foreach ($approvedLogsUpToBucket as $idx => $lb) {
                        $logDate = \Carbon\Carbon::parse($lb->date);
                        $dateLabel = $logDate->translatedFormat('d M') ?: $logDate->format('d/m');

                        $lbProgress = (float) $lb->progress;
                        // Progres bertambah seiring laporan yang disetujui
                        $runningActual = max($runningActual, $lbProgress);

                        // Hitung planning pada tanggal logbook tersebut
                        $planAtDate = $calcPlanningAt($task, $logDate->copy()->endOfDay());
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
                    $lastLogDate = \Carbon\Carbon::parse($approvedLogsUpToBucket->last()->date)->endOfDay();
                    if ($bEnd->gt($lastLogDate)) {
                        $finalPlan = $calcPlanningAt($task, $bEnd);
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

                        $wkPlan = $calcPlanningAt($task, $weekEnd);
                        $weekRunningPlanning = max($weekRunningPlanning, (float) $wkPlan);
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
                ? ($lastLog->date ? \Carbon\Carbon::parse($lastLog->date)->format('M d, Y_ h:i A') : ($lastLog->created_at ? $lastLog->created_at->format('M d, Y_ h:i A') : '-'))
                : ($task->updated_at ? $task->updated_at->format('M d, Y_ h:i A') : '-');

            $monthlyChartTasks[] = [
                'task_id' => $task->id,
                'task_number' => $taskNumber,
                'task_name' => $task->title,
                'target_name' => $task->target?->title ?? '-',
                'sub_target_name' => $task->subTarget?->title ?? '-',
                'division_name' => $task->division?->name ?? 'Tanpa Divisi',
                'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
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
     * Diagram Kontribusi Sub Target (Single-circle aggregated pie)
     * Mengikutsertakan seluruh target departemen beserta sub targetnya
     */
    $pieContributionData = [];
    $chartTasksBySubTarget = $chartTasks->groupBy('sub_target_id');
    $deptTargetsForPie = $department->targets;
    if ($deptTargetsForPie->isEmpty()) {
        $deptTargetsForPie = \App\Models\Target::where('department_id', $department->id)->with(['subTargets', 'division'])->latest()->get();
    } else {
        $deptTargetsForPie->loadMissing(['subTargets', 'division']);
    }

    foreach ($deptTargetsForPie as $target) {
        $subTargets = $target->subTargets;
        if ($subTargets->isEmpty()) {
            continue;
        }

        $subTargetCount = $subTargets->count();

        foreach ($subTargets as $subTarget) {
            $subTargetTasks = $chartTasksBySubTarget->get($subTarget->id, collect());
            if ($subTargetTasks->isNotEmpty()) {
                $progress = $subTargetTasks->avg(function ($task) use ($endDate) {
                    $latestApprovedLog = $task->logbooks
                        ? $task->logbooks
                            ->where('status', 'approved')
                            ->filter(fn($lb) => $lb->date && \Carbon\Carbon::parse($lb->date)->lte($endDate))
                            ->sortByDesc('date')
                            ->sortByDesc('id')
                            ->first()
                        : null;

                    $prog = $latestApprovedLog
                        ? (float) $latestApprovedLog->progress
                        : (float) ($task->verified_progress ?? 0);

                    return min(max($prog, 0), 100);
                });
            } else {
                $progress = (float) ($subTarget->progress ?? 0);
            }

            $progress = round((float) $progress, 2);
            $percentage = (float) ($subTarget->percentage ?? 0);
            if ($percentage <= 0 && $subTargetCount > 0) {
                $percentage = round(100 / $subTargetCount, 2);
            }
            $percentage = min(max($percentage, 0), 100);
            $contribution = round($progress * $percentage / 100, 2);

            $taskList = $subTargetTasks->map(function ($task) use ($endDate) {
                $latestApprovedLog = $task->logbooks
                    ? $task->logbooks
                        ->where('status', 'approved')
                        ->filter(fn($lb) => $lb->date && \Carbon\Carbon::parse($lb->date)->lte($endDate))
                        ->sortByDesc('date')
                        ->sortByDesc('id')
                        ->first()
                    : null;
                $prog = $latestApprovedLog
                    ? (float) $latestApprovedLog->progress
                    : (float) ($task->verified_progress ?? 0);
                return [
                    'task_id' => $task->id,
                    'task_name' => $task->title,
                    'employee_id' => $task->assigned_to,
                    'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
                    'progress' => round(min(max($prog, 0), 100), 1),
                ];
            })->values()->all();

            $pieContributionData[] = [
                'label' => $target->title . ' — ' . $subTarget->title,
                'target_id' => $target->id,
                'target_name' => $target->title,
                'division_id' => $target->division_id,
                'division_name' => $target->division?->name ?? 'Divisi Umum',
                'sub_target_id' => $subTarget->id,
                'sub_target_name' => $subTarget->title,
                'percentage' => round($percentage, 2),
                'progress' => round($progress, 2),
                'contribution' => round($contribution, 2),
                'remaining' => round(max(0, $percentage - $contribution), 2),
                'tasks' => $taskList,
            ];
        }
    }

    $kepalaDivisiList = User::where(
        'department_id',
        auth()->user()->department_id
    )
        ->where('role', 'kepala_divisi')
        ->orderBy('name')
        ->get();

    $divisions = \App\Models\Division::where(
        'department_id',
        auth()->user()->department_id
    )
        ->orderBy('name')
        ->get();

    $activeTargets = \App\Models\Target::where(
        'department_id',
        auth()->user()->department_id
    )
        ->where('status', 'active')
        ->latest()
        ->get();

    // Nilai kompatibilitas ke belakang
    $availableChartYears = range((int) now()->year - 2, (int) now()->year + 1);
    $selectedChartYear = (int) $startDate->year;
    $currentMonth = (int) now()->month;
    $selectedChartMonths = [(int) $startDate->month];

    /*
    |--------------------------------------------------------------------------
    | KIRIM DATA KE DASHBOARD
    |--------------------------------------------------------------------------
    */

    return view(
        'kepala-departemen.dashboard',
        compact(
            'department',
            'totalEmployees',
            'totalKepalaDivisi',
            'totalDivisions',
            'totalTargets',
            'totalSubTargets',
            'employees',
            'kepalaDivisiList',
            'divisions',
            'activeTargets',
            'divisionEmployees',
            'divisionHeads',
            'divisionTaskProgress',
            'targetTaskMonitoring',
            'totalLogbooks',
            'allDepartmentLogbooks',
            'logbookMonitoring',
            'monthlyChartTasks',
            'chartTaskCatalog',
            'chartEmployees',
            'pieContributionData',
            'startDate',
            'endDate',
            'preset',
            'filterMonth',
            'filterYear',
            'groupingType',
            'groupingLabel',
            'diffDays',
            'availableChartYears',
            'selectedChartYear',
            'currentMonth',
            'monthNames',
            'selectedChartMonths',
            'selectedChartDivisionId',
            'selectedChartTargetId',
            'selectedChartSubTargetId',
            'selectedChartEmployeeId'
        )
    );
}
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD KEPALA DIVISI
    |--------------------------------------------------------------------------
    */

    public function kepalaDivisi(Request $request)
    {
        $user = auth()->user();

        $department = Department::findOrFail(
            $user->department_id
        );

        $division = Division::findOrFail(
            $user->division_id
        );

        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'preset' => ['nullable', 'string', Rule::in(['7_days', '30_days', 'this_month', 'last_month', 'this_year', 'month_picker', 'custom'])],
            'filter_month' => ['nullable', 'integer', 'between:1,12'],
            'filter_year' => ['nullable', 'integer'],
            'employee_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) use ($user) {
                    $query
                        ->where('department_id', $user->department_id)
                        ->where('division_id', $user->division_id)
                        ->where('role', 'employee');
                }),
            ],
        ], [
            'end_date.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal mulai.',
            'start_date.date' => 'Format tanggal mulai tidak valid.',
            'end_date.date' => 'Format tanggal akhir tidak valid.',
        ]);

        $selectedChartDivisionId = $user->division_id;

        $selectedChartEmployeeId = $request->filled('employee_id')
            ? (int) $request->query('employee_id')
            : null;

        $preset = $request->query('preset');
        $startDateInput = $request->query('start_date');
        $endDateInput = $request->query('end_date');
        $filterMonth = $request->query('filter_month');
        $filterYear = $request->query('filter_year');

        // Tentukan rentang waktu berdasarkan preset atau input tanggal manual (sistem BRImo)
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
            $startDate = \Carbon\Carbon::create((int) $filterYear, (int) $filterMonth, 1)->startOfDay();
            $endDate = \Carbon\Carbon::create((int) $filterYear, (int) $filterMonth, 1)->endOfMonth()->endOfDay();
        } elseif ($startDateInput && $endDateInput) {
            $startDate = \Carbon\Carbon::parse($startDateInput)->startOfDay();
            $endDate = \Carbon\Carbon::parse($endDateInput)->endOfDay();
            if ($startDate->gt($endDate)) {
                $endDate = $startDate->copy()->endOfDay();
            }
            $preset = $preset ?: 'custom';
        } else {
            $preset = 'this_month';
            $startDate = now()->startOfMonth()->startOfDay();
            $endDate = now()->endOfMonth()->endOfDay();
        }

        $filterMonth = (int) ($filterMonth ?: $startDate->month);
        $filterYear = (int) ($filterYear ?: $startDate->year);
        $diffDays = (int) ($startDate->diffInDays($endDate) + 1);

        $targets = Target::where(
            'department_id',
            $user->department_id
        )
            ->where(
                'division_id',
                $user->division_id
            )
            ->where(
                'status',
                'active'
            )
            ->with([
                'subTargets.tasks',
            ])
            ->latest()
            ->get();

        $employees = User::where('department_id', $department->id)
            ->where('division_id', $user->division_id)
            ->where('role', 'employee')
            ->with('divisionRelation')
            ->orderBy('name')
            ->get();

        $totalEmployees = $employees->count();

        $allTasks = Task::with(['target', 'assignedTo'])
            ->where('assigned_by', $user->id)
            ->latest()
            ->get();

        $assignedTaskList = $allTasks
            ->where('status', 'assigned')
            ->values();

        $inProgressTaskList = $allTasks
            ->where('status', 'in_progress')
            ->values();

        $revisionTaskList = $allTasks
            ->where('status', 'revision')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | HITUNG PROGRESS TARGET
        |--------------------------------------------------------------------------
        */
        $targets->each(function ($target) {
            $totalProgress = 0;

            foreach ($target->subTargets as $subTarget) {
                $weight = (float) ($subTarget->percentage ?? 0);

                $highestProgress = $subTarget->tasks->max(
                    'verified_progress'
                ) ?? 0;

                $highestProgress = min(
                    100,
                    max(0, (float) $highestProgress)
                );

                $totalProgress += (
                    $weight * $highestProgress
                ) / 100;
            }

            $target->calculated_progress = round(
                min(100, max(0, $totalProgress)),
                1
            );
        });

        $totalTasks = Task::where(
            'assigned_by',
            $user->id
        )->count();

        $assignedTasks = Task::where(
            'assigned_by',
            $user->id
        )
            ->where(
                'status',
                'assigned'
            )
            ->count();

        $inProgressTasks = Task::where(
            'assigned_by',
            $user->id
        )
            ->where(
                'status',
                'in_progress'
            )
            ->count();

        $revisionTasks = Task::where(
            'assigned_by',
            $user->id
        )
            ->where(
                'status',
                'revision'
            )
            ->count();

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
        } else {
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

        // Karyawan hanya dalam divisi kepala divisi itu sendiri
        $chartEmployees = User::query()
            ->where('department_id', $department->id)
            ->where('division_id', $user->division_id)
            ->where('role', 'employee')
            ->orderBy('name')
            ->get(['id', 'name', 'division_id']);

        // Query task dibatasi pada divisi sendiri dan karyawan terpilih
        $chartTasks = Task::query()
            ->where('department_id', $department->id)
            ->where('division_id', $user->division_id)
            ->when($selectedChartEmployeeId, function ($query) use ($selectedChartEmployeeId) {
                $query->where('assigned_to', $selectedChartEmployeeId);
            })
            ->with([
                'subTarget',
                'target',
                'assignedTo',
                'division',
                'logbooks' => function ($query) {
                    $query->orderBy('date');
                }
            ])
            ->orderBy('title')
            ->orderBy('id')
            ->get();

        $calcPlanningAt = function ($task, \Carbon\Carbon $asOf): float {
            $subTarget = $task->subTarget;

            if (!$subTarget || !$subTarget->period_start || !$subTarget->period_end) {
                $workType = strtolower(trim((string) $task->work_type));
                if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
                    return $task->created_at && \Carbon\Carbon::parse($task->created_at)->lte($asOf) ? 100.0 : 0.0;
                }
                return (float) ($task->target_progress ?? 0);
            }

            $workType = strtolower(trim((string) $task->work_type));
            if (in_array($workType, ['other', 'lainnya', 'dadakan'], true)) {
                return $task->created_at && \Carbon\Carbon::parse($task->created_at)->lte($asOf) ? 100.0 : 0.0;
            }

            $start = \Carbon\Carbon::parse($subTarget->period_start)->startOfDay();
            $end   = \Carbon\Carbon::parse($subTarget->period_end)->endOfDay();

            if ($asOf->lt($start)) return 0.0;
            if ($asOf->gte($end))  return 100.0;

            $totalDays   = max(1, $start->diffInDays($end));
            $elapsedDays = $start->diffInDays($asOf);

            return round(min(max(($elapsedDays / $totalDays) * 100, 0), 100), 2);
        };

        $monthlyChartTasks = [];
        $chartTaskCatalog = [];

        foreach ($chartTasks as $taskIndex => $task) {
            $taskNumber = $taskIndex + 1;
            $chartTaskCatalog[] = [
                'task_number' => $taskNumber,
                'task_name' => $task->title,
                'division_name' => $task->division?->name ?? $division->name,
                'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
            ];

            $taskCreated = $task->created_at
                ? \Carbon\Carbon::parse($task->created_at)
                : null;

            $taskLogbooks = $task->logbooks ? $task->logbooks->sortBy('date') : collect();
            $firstLogDate = $taskLogbooks->first()?->date ? \Carbon\Carbon::parse($taskLogbooks->first()->date) : null;
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
                    $rawPlanning = $calcPlanningAt($task, $bEnd);

                    $approvedLogsUpToBucket = $taskLogbooks
                        ->where('status', 'approved')
                        ->filter(function ($lb) use ($bEnd) {
                            return $lb->date && \Carbon\Carbon::parse($lb->date)->lte($bEnd);
                        })
                        ->sortBy('date')
                        ->values();

                    if ($approvedLogsUpToBucket->isNotEmpty()) {
                        $latestApprovedLog = $approvedLogsUpToBucket->last();
                        $actual = (float) $latestApprovedLog->progress;
                    } else {
                        $actual = (float) ($task->verified_progress ?? 0);
                    }

                    $planning = max((float) $rawPlanning, (float) $actual);
                    $planning = round(min(max((float) $planning, 0), 100), 1);
                    $actual = round(min(max((float) $actual, 0), 100), 1);

                    $drilldownData = [];

                    if ($approvedLogsUpToBucket->isNotEmpty()) {
                        $subTargetStart = $task->subTarget?->period_start ? \Carbon\Carbon::parse($task->subTarget->period_start)->startOfDay() : null;
                        $firstAppDate = \Carbon\Carbon::parse($approvedLogsUpToBucket->first()->date);
                        $initialPointDate = $subTargetStart && $subTargetStart->lt($firstAppDate) ? $subTargetStart : $firstAppDate->copy()->subDay();

                        $initPlan = $calcPlanningAt($task, $initialPointDate);
                        $drilldownData[] = [
                            'week'     => 1,
                            'label'    => 'Mulai',
                            'planning' => round(min(max((float) $initPlan, 0), 100), 1),
                            'actual'   => 0.0,
                        ];

                        $runningActual = 0.0;
                        foreach ($approvedLogsUpToBucket as $idx => $lb) {
                            $logDate = \Carbon\Carbon::parse($lb->date);
                            $dateLabel = $logDate->translatedFormat('d M') ?: $logDate->format('d/m');

                            $lbProgress = (float) $lb->progress;
                            $runningActual = max($runningActual, $lbProgress);

                            $planAtDate = $calcPlanningAt($task, $logDate->copy()->endOfDay());
                            $planAtDate = max((float) $planAtDate, (float) $runningActual);

                            $drilldownData[] = [
                                'week'     => $idx + 2,
                                'label'    => $dateLabel,
                                'planning' => round(min(max((float) $planAtDate, 0), 100), 1),
                                'actual'   => round(min(max((float) $runningActual, 0), 100), 1),
                            ];
                        }

                        $lastLogDate = \Carbon\Carbon::parse($approvedLogsUpToBucket->last()->date)->endOfDay();
                        if ($bEnd->gt($lastLogDate)) {
                            $finalPlan = $calcPlanningAt($task, $bEnd);
                            $finalPlan = max((float) $finalPlan, (float) $runningActual);

                            $drilldownData[] = [
                                'week'     => count($drilldownData) + 1,
                                'label'    => 'Saat Ini',
                                'planning' => round(min(max((float) $finalPlan, 0), 100), 1),
                                'actual'   => round(min(max((float) $runningActual, 0), 100), 1),
                            ];
                        }
                    } else {
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

                            $wkPlan = $calcPlanningAt($task, $weekEnd);
                            $weekRunningPlanning = max($weekRunningPlanning, (float) $wkPlan);
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
                    ? ($lastLog->date ? \Carbon\Carbon::parse($lastLog->date)->format('M d, Y_ h:i A') : ($lastLog->created_at ? $lastLog->created_at->format('M d, Y_ h:i A') : '-'))
                    : ($task->updated_at ? $task->updated_at->format('M d, Y_ h:i A') : '-');

                $monthlyChartTasks[] = [
                    'task_id' => $task->id,
                    'task_number' => $taskNumber,
                    'task_name' => $task->title,
                    'target_name' => $task->target?->title ?? '-',
                    'sub_target_name' => $task->subTarget?->title ?? '-',
                    'division_name' => $task->division?->name ?? $division->name,
                    'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
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
        | DIAGRAM KONTRIBUSI SUB TARGET (SINGLE NESTED DONUT CHART)
        |--------------------------------------------------------------------------
        |
        | Khusus kepala divisi, hanya memantau target & sub target divisinya sendiri.
        |
        */
        $pieContributionData = [];
        $chartTasksBySubTarget = $chartTasks->groupBy('sub_target_id');
        $divTargetsForPie = Target::where('department_id', $department->id)
            ->where('division_id', $user->division_id)
            ->where('status', 'active')
            ->with(['subTargets', 'division'])
            ->latest()
            ->get();

        foreach ($divTargetsForPie as $target) {
            $subTargets = $target->subTargets;
            if ($subTargets->isEmpty()) {
                continue;
            }

            $subTargetCount = $subTargets->count();

            foreach ($subTargets as $subTarget) {
                $subTargetTasks = $chartTasksBySubTarget->get($subTarget->id, collect());
                if ($subTargetTasks->isNotEmpty()) {
                    $progress = $subTargetTasks->avg(function ($task) use ($endDate) {
                        $latestApprovedLog = $task->logbooks
                            ? $task->logbooks
                                ->where('status', 'approved')
                                ->filter(fn($lb) => $lb->date && \Carbon\Carbon::parse($lb->date)->lte($endDate))
                                ->sortByDesc('date')
                                ->sortByDesc('id')
                                ->first()
                            : null;

                        $prog = $latestApprovedLog
                            ? (float) $latestApprovedLog->progress
                            : (float) ($task->verified_progress ?? 0);

                        return min(max($prog, 0), 100);
                    });
                } else {
                    $progress = (float) ($subTarget->progress ?? 0);
                }

                $progress = round((float) $progress, 2);
                $percentage = (float) ($subTarget->percentage ?? 0);
                if ($percentage <= 0 && $subTargetCount > 0) {
                    $percentage = round(100 / $subTargetCount, 2);
                }
                $percentage = min(max($percentage, 0), 100);
                $contribution = round($progress * $percentage / 100, 2);

                $taskList = $subTargetTasks->map(function ($task) use ($endDate) {
                    $latestApprovedLog = $task->logbooks
                        ? $task->logbooks
                            ->where('status', 'approved')
                            ->filter(fn($lb) => $lb->date && \Carbon\Carbon::parse($lb->date)->lte($endDate))
                            ->sortByDesc('date')
                            ->sortByDesc('id')
                            ->first()
                        : null;
                    $prog = $latestApprovedLog
                        ? (float) $latestApprovedLog->progress
                        : (float) ($task->verified_progress ?? 0);
                    return [
                        'task_id' => $task->id,
                        'task_name' => $task->title,
                        'employee_id' => $task->assigned_to,
                        'employee_name' => $task->assignedTo?->name ?? 'Belum ditentukan',
                        'progress' => round(min(max($prog, 0), 100), 1),
                    ];
                })->values()->all();

                $pieContributionData[] = [
                    'label' => $target->title . ' — ' . $subTarget->title,
                    'target_id' => $target->id,
                    'target_name' => $target->title,
                    'division_id' => $target->division_id,
                    'division_name' => $target->division?->name ?? $division->name,
                    'sub_target_id' => $subTarget->id,
                    'sub_target_name' => $subTarget->title,
                    'percentage' => round($percentage, 2),
                    'progress' => round($progress, 2),
                    'contribution' => round($contribution, 2),
                    'remaining' => round(max(0, $percentage - $contribution), 2),
                    'tasks' => $taskList,
                ];
            }
        }

        $availableChartYears = range((int) now()->year - 2, (int) now()->year + 1);
        $selectedChartYear = (int) $startDate->year;
        $currentMonth = (int) now()->month;
        $selectedChartMonths = [(int) $startDate->month];

        return view(
            'kepala-divisi.dashboard',
            compact(
                'user',
                'department',
                'division',
                'targets',
                'totalEmployees',
                'totalTasks',
                'assignedTasks',
                'inProgressTasks',
                'revisionTasks',
                'employees',
                'allTasks',
                'assignedTaskList',
                'inProgressTaskList',
                'revisionTaskList',
                'monthlyChartTasks',
                'chartTaskCatalog',
                'chartEmployees',
                'pieContributionData',
                'startDate',
                'endDate',
                'preset',
                'filterMonth',
                'filterYear',
                'groupingType',
                'groupingLabel',
                'diffDays',
                'availableChartYears',
                'selectedChartYear',
                'currentMonth',
                'monthNames',
                'selectedChartMonths',
                'selectedChartDivisionId',
                'selectedChartEmployeeId'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DAFTAR PEKERJAAN KARYAWAN
    |--------------------------------------------------------------------------
    */

    public function employeeTasks()
    {
        $user = auth()->user();

        $tasks = Task::with([
            'target',
            'assignedBy',
            'division',
        ])
            ->where(
                'assigned_to',
                $user->id
            )
            ->latest()
            ->get();

        return view(
            'employee.tasks.index',
            compact('tasks')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD PIMPINAN
    |--------------------------------------------------------------------------
    */

public function pimpinan()
{
    /*
    |--------------------------------------------------------------------------
    | RINGKASAN ORGANISASI
    |--------------------------------------------------------------------------
    */

    $totalDepartments = Department::count();

    $totalDivisions = Division::count();

    $totalEmployees = User::where(
        'role',
        'employee'
    )->count();

    $totalKepalaDivisi = User::where(
        'role',
        'kepala_divisi'
    )->count();

    $totalActiveTargets = \App\Models\Target::where(
        'status',
        'active'
    )->count();

    $totalTasks = Task::count();

    $totalLogbooks = Logbook::count();


    /*
    |--------------------------------------------------------------------------
    | PROGRESS PEKERJAAN PER DEPARTEMEN
    |--------------------------------------------------------------------------
    */

        $departmentTaskProgress = Task::select(
        'department_id',
        DB::raw('COUNT(*) as total_tasks'),
        DB::raw('SUM(weight) as total_weight'),
        DB::raw('SUM(verified_progress * weight) as weighted_progress')
    )
        ->whereNotNull('department_id')
        ->groupBy('department_id')
        ->with('department')
        ->get()
        ->map(function ($item) {
            $item->progress = $item->total_weight > 0
                ? round(
                    $item->weighted_progress / $item->total_weight,
                    1
                )
                : 0;

            return $item;
        });

    
    /*
    |--------------------------------------------------------------------------
    | TARGET AKTIF PER DEPARTEMEN
    |--------------------------------------------------------------------------
    */

    $departmentTargets = Department::with([
        'targets' => function ($query) {
            $query
                ->where('status', 'active')
                ->latest();
        }
    ])
        ->get();


    /*
    |--------------------------------------------------------------------------
    | AKTIVITAS LOGBOOK TERBARU
    |--------------------------------------------------------------------------
    */

    $recentLogbooks = Logbook::with([
        'user',
        'task.department'
    ])
        ->latest()
        ->take(44)
        ->get();


    /*
    |--------------------------------------------------------------------------
    | STATUS PEKERJAAN
    |--------------------------------------------------------------------------
    */

    $taskStatus = [
        'assigned' => Task::where(
            'status',
            'assigned'
        )->count(),

        'in_progress' => Task::where(
            'status',
            'in_progress'
        )->count(),

        'revision' => Task::where(
            'status',
            'revision'
        )->count(),

        'completed' => Task::where(
            'status',
            'completed'
        )->count(),
    ];


    /*
    |--------------------------------------------------------------------------
    | FILTER TAHUN
    |--------------------------------------------------------------------------
    |
    | Pimpinan dapat memilih tahun untuk melihat
    | tren aktivitas logbook.
    |
    */

    $selectedYear = (int) request(
        'year',
        now()->year
    );


    /*
    |--------------------------------------------------------------------------
    | VALIDASI TAHUN
    |--------------------------------------------------------------------------
    */

    if (
        $selectedYear < 2020 ||
        $selectedYear > now()->year + 1
    ) {
        $selectedYear = now()->year;
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL LOGBOOK PER BULAN
    |--------------------------------------------------------------------------
    */

    $monthlyLogbooks = Logbook::select(
        DB::raw('MONTH(date) as month'),
        DB::raw('COUNT(*) as total')
    )
        ->whereYear(
            'date',
            $selectedYear
        )
        ->groupBy(
            DB::raw('MONTH(date)')
        )
        ->orderBy(
            DB::raw('MONTH(date)')
        )
        ->pluck(
            'total',
            'month'
        );


    /*
    |--------------------------------------------------------------------------
    | LOGBOOK DISETUJUI PER BULAN
    |--------------------------------------------------------------------------
    */

    $monthlyApproved = Logbook::select(
        DB::raw('MONTH(date) as month'),
        DB::raw('COUNT(*) as total')
    )
        ->whereYear(
            'date',
            $selectedYear
        )
        ->where(
            'status',
            'approved'
        )
        ->groupBy(
            DB::raw('MONTH(date)')
        )
        ->orderBy(
            DB::raw('MONTH(date)')
        )
        ->pluck(
            'total',
            'month'
        );


    /*
    |--------------------------------------------------------------------------
    | LOGBOOK REVISI PER BULAN
    |--------------------------------------------------------------------------
    */

    $monthlyRevision = Logbook::select(
        DB::raw('MONTH(date) as month'),
        DB::raw('COUNT(*) as total')
    )
        ->whereYear(
            'date',
            $selectedYear
        )
        ->where(
            'status',
            'revision'
        )
        ->groupBy(
            DB::raw('MONTH(date)')
        )
        ->orderBy(
            DB::raw('MONTH(date)')
        )
        ->pluck(
            'total',
            'month'
        );


    /*
    |--------------------------------------------------------------------------
    | LABEL BULAN
    |--------------------------------------------------------------------------
    */

    $monthLabels = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'Mei',
        'Jun',
        'Jul',
        'Agu',
        'Sep',
        'Okt',
        'Nov',
        'Des',
    ];


    /*
    |--------------------------------------------------------------------------
    | DATA CHART LOGBOOK
    |--------------------------------------------------------------------------
    */

    $monthlyLogbookChart = [];

    for (
        $month = 1;
        $month <= 12;
        $month++
    ) {

        $monthlyLogbookChart[] = [
            'label' => $monthLabels[$month - 1],

            'total' => (int) (
                $monthlyLogbooks[$month] ?? 0
            ),

            'approved' => (int) (
                $monthlyApproved[$month] ?? 0
            ),

            'revision' => (int) (
                $monthlyRevision[$month] ?? 0
            ),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | TAHUN YANG TERSEDIA
    |--------------------------------------------------------------------------
    */

    $availableYears = Logbook::selectRaw(
        'YEAR(date) as year'
    )
        ->whereNotNull('date')
        ->distinct()
        ->orderByDesc('year')
        ->pluck('year');


    /*
    |--------------------------------------------------------------------------
    | PASTIKAN TAHUN YANG DIPILIH ADA DI FILTER
    |--------------------------------------------------------------------------
    */

    if (!$availableYears->contains($selectedYear)) {
        $availableYears->push($selectedYear);
    }

    $availableYears = $availableYears
        ->unique()
        ->sortDesc()
        ->values();


    /*
    |--------------------------------------------------------------------------
    | KIRIM DATA KE DASHBOARD PIMPINAN
    |--------------------------------------------------------------------------
    */

    return view(
        'pimpinan.dashboard',
        compact(
            'totalDepartments',
            'totalDivisions',
            'totalEmployees',
            'totalKepalaDivisi',
            'totalActiveTargets',
            'totalTasks',
            'totalLogbooks',
            'departmentTaskProgress',
            'departmentTargets',
            'recentLogbooks',
            'taskStatus',
            'selectedYear',
            'monthlyLogbookChart',
            'availableYears'
        )
    );
}


/*
|--------------------------------------------------------------------------
| MONITORING KARYAWAN PIMPINAN
|--------------------------------------------------------------------------
*/

public function pimpinanEmployees()
{
    $employees = User::query()
        ->where('role', 'employee')
        ->with([
            'department',
            'divisionRelation',
        ])
        ->withCount([
            'assignedTasks',
            'logbooks',
        ])
        ->orderBy('name')
        ->get();

    return view(
        'pimpinan.employees.index',
        compact('employees')
    );
}
/*
|--------------------------------------------------------------------------
| DETAIL MONITORING KARYAWAN PIMPINAN
|--------------------------------------------------------------------------
*/

public function pimpinanEmployeeDetail(User $user)
{
    /*
    |--------------------------------------------------------------------------
    | PASTIKAN USER YANG DIBUKA ADALAH KARYAWAN
    |--------------------------------------------------------------------------
    */

    abort_unless(
        $user->role === 'employee',
        404
    );


    /*
    |--------------------------------------------------------------------------
    | DATA KARYAWAN
    |--------------------------------------------------------------------------
    */

    $user->load([
        'department',
        'divisionRelation',
    ]);


    /*
    |--------------------------------------------------------------------------
    | PEKERJAAN KARYAWAN
    |--------------------------------------------------------------------------
    */

    $tasks = Task::with([
        'target',
        'assignedBy',
    ])
        ->where(
            'assigned_to',
            $user->id
        )
        ->latest()
        ->get();


        /*
        |--------------------------------------------------------------------------
        | FILTER PERIODE
        |--------------------------------------------------------------------------
        */

        $selectedYear = (int) request(
            'year',
            now()->year
        );

        $selectedMonth = request('month');


        if (
            $selectedYear < 2020 ||
            $selectedYear > now()->year + 1
        ) {
            $selectedYear = now()->year;
        }


        /*
        |--------------------------------------------------------------------------
        | QUERY LOGBOOK KARYAWAN
        |--------------------------------------------------------------------------
        */

        $logbooksQuery = Logbook::with([
            'task.target',
            'task.assignedBy',
        ])
            ->where(
                'user_id',
                $user->id
            )
            ->whereYear(
                'date',
                $selectedYear
            );


        if (
            $selectedMonth !== null &&
            $selectedMonth !== '' &&
            (int) $selectedMonth >= 1 &&
            (int) $selectedMonth <= 12
        ) {
            $logbooksQuery->whereMonth(
                'date',
                (int) $selectedMonth
            );

            $selectedMonth = (int) $selectedMonth;
        } else {
            $selectedMonth = '';
        }


        $logbooks = $logbooksQuery
            ->latest('date')
            ->latest('id')
            ->get();


    /*
    |--------------------------------------------------------------------------
    | STATISTIK PEKERJAAN
    |--------------------------------------------------------------------------
    */

    $totalTasks = $tasks->count();

    $completedTasks = $tasks
        ->where('status', 'completed')
        ->count();

    $inProgressTasks = $tasks
        ->where('status', 'in_progress')
        ->count();

    $revisionTasks = $tasks
        ->where('status', 'revision')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | STATISTIK LOGBOOK
    |--------------------------------------------------------------------------
    */

    $totalLogbooks = $logbooks->count();

    $approvedLogbooks = $logbooks
        ->where('status', 'approved')
        ->count();

    $revisionLogbooks = $logbooks
        ->where('status', 'revision')
        ->count();

    $pendingLogbooks = $logbooks
        ->where('status', 'pending')
        ->count();


    /*
    |--------------------------------------------------------------------------
    | RATA-RATA PROGRESS LOGBOOK
    |--------------------------------------------------------------------------
    */

    $averageProgress = $logbooks->isNotEmpty()
        ? round(
            $logbooks->avg('progress'),
            1
        )
        : 0;


    /*
    |--------------------------------------------------------------------------
    | DATA LOGBOOK UNTUK MONITORING
    |--------------------------------------------------------------------------
    */

    $logbookActivities = $logbooks
        ->take(10);


    /*
    |--------------------------------------------------------------------------
    | KIRIM KE HALAMAN DETAIL
    |--------------------------------------------------------------------------
    */

    return view(
        'pimpinan.employees.show',
        compact(
            'user',
            'tasks',
            'logbooks',
            'totalTasks',
            'completedTasks',
            'inProgressTasks',
            'revisionTasks',
            'totalLogbooks',
            'approvedLogbooks',
            'revisionLogbooks',
            'pendingLogbooks',
            'averageProgress',
            'logbookActivities',
            'selectedYear',
'selectedMonth',
        )
    );
}

}
