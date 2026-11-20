<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeaderLogbookController extends Controller
{
    /**
     * Menampilkan daftar karyawan beserta jumlah logbook
     * yang telah direview.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'employee')
            ->with([
                'logbooks' => function ($query) {
                    $query
                        ->whereIn('status', ['approved', 'rejected'])
                        ->latest('date');
                }
            ])
            ->withCount([
                'logbooks as total_logbooks_count' => function ($query) {
                    $query->whereIn(
                        'status',
                        ['approved', 'rejected']
                    );
                },

                'logbooks as approved_logbooks_count' => function ($query) {
                    $query->where(
                        'status',
                        'approved'
                    );
                },

                'logbooks as rejected_logbooks_count' => function ($query) {
                    $query->where(
                        'status',
                        'rejected'
                    );
                },
            ]);

        /*
        |--------------------------------------------------------------------------
        | Filter Nama
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(
                'name',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Divisi
        |--------------------------------------------------------------------------
        */

        if ($request->filled('division')) {
            $query->where(
                'division',
                $request->division
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Bulan
        |--------------------------------------------------------------------------
        */

        if ($request->filled('month')) {

            [$year, $month] = array_pad(
                explode('-', $request->month),
                2,
                null
            );

            $query->whereHas(
                'logbooks',
                function ($logbook) use ($year, $month) {

                    $logbook
                        ->whereMonth('date', $month)
                        ->whereYear('date', $year)
                        ->whereIn(
                            'status',
                            ['approved', 'rejected']
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Tahun
        |--------------------------------------------------------------------------
        */

        if ($request->filled('year')) {

            $query->whereHas(
                'logbooks',
                function ($logbook) use ($request) {

                    $logbook
                        ->whereYear(
                            'date',
                            $request->year
                        )
                        ->whereIn(
                            'status',
                            ['approved', 'rejected']
                        );
                }
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $employees = $query
            ->orderByDesc('total_logbooks_count')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Data Divisi
        |--------------------------------------------------------------------------
        */

        $divisions = Division::orderBy('name')
            ->pluck('name');

        return view(
            'leader.logbooks.index',
            compact(
                'employees',
                'divisions'
            )
        );
    }


    /**
     * Menampilkan detail logbook seorang karyawan.
     *
     * Leader hanya melihat logbook yang telah direview.
     */
    public function employeeDetail(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan user adalah employee
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->role === 'employee',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Ambil Logbook
        |--------------------------------------------------------------------------
        */

        $logbooks = $user->logbooks()
            ->whereIn(
                'status',
                ['approved', 'rejected']
            )
            ->with([
                'reviewer',
                'comments.user'
            ])
            ->latest('date')
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $total = $logbooks->count();

        $approved = $logbooks
            ->where(
                'status',
                'approved'
            )
            ->count();

        $rejected = $logbooks
            ->where(
                'status',
                'rejected'
            )
            ->count();

        $reviewed = $approved + $rejected;

        /*
        |--------------------------------------------------------------------------
        | Approval Rate
        |--------------------------------------------------------------------------
        */

        $approvalRate = $reviewed > 0
            ? round(
                ($approved / $reviewed) * 100
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Average Progress
        |--------------------------------------------------------------------------
        */

        // Progres diambil dari logbook yang disetujui terbaru (akumulasi progres, bukan dirata-ratakan)
        $latestApproved = $logbooks
            ->where(
                'status',
                'approved'
            )
            ->whereNotNull('progress')
            ->sortByDesc('date')
            ->sortByDesc('id')
            ->first();

        $avgProgress = $latestApproved !== null
            ? round(
                (float) $latestApproved->progress,
                1
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Kirim Data ke View
        |--------------------------------------------------------------------------
        */

        return view(
            'leader.logbooks.employee-detail',
            [
                'user' => $user,
                'logbooks' => $logbooks,
                'total' => $total,
                'approved' => $approved,
                'rejected' => $rejected,
                'approvalRate' => $approvalRate,
                'avgProgress' => $avgProgress,
            ]
        );
    }



    /**
     * Menampilkan ringkasan bulanan logbook karyawan.
     *
     * Tidak menggunakan AI/Gemini — semua diproses lokal.
     */
    public function monthlySummary(Request $request, User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan user adalah employee
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $user->role === 'employee',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Tentukan bulan & tahun yang difilter
        |--------------------------------------------------------------------------
        */

        $monthParam = $request->get('month', now()->format('Y-m'));

        [$year, $month] = array_pad(
            explode('-', $monthParam),
            2,
            null
        );

        $year  = (int) ($year  ?: now()->year);
        $month = (int) ($month ?: now()->month);

        /*
        |--------------------------------------------------------------------------
        | Ambil daftar bulan yang tersedia (untuk dropdown filter)
        |--------------------------------------------------------------------------
        */

        $availableMonths = $user->logbooks()
            ->whereIn('status', ['approved', 'rejected'])
            ->selectRaw("DATE_FORMAT(date, '%Y-%m') as month_key")
            ->groupBy('month_key')
            ->orderByDesc('month_key')
            ->pluck('month_key');

        /*
        |--------------------------------------------------------------------------
        | Ambil logbook bulan yang dipilih
        |--------------------------------------------------------------------------
        */

        $logbooks = $user->logbooks()
            ->whereIn('status', ['approved', 'rejected'])
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->with(['reviewer', 'comments.user'])
            ->latest('date')
            ->latest('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Statistik
        |--------------------------------------------------------------------------
        */

        $total    = $logbooks->count();
        $approved = $logbooks->where('status', 'approved')->count();
        $rejected = $logbooks->where('status', 'rejected')->count();

        $approvalRate = $total > 0
            ? round(($approved / $total) * 100)
            : 0;

        // Progres diambil dari logbook yang disetujui terbaru dalam bulan tersebut
        $latestApproved = $logbooks
            ->where('status', 'approved')
            ->whereNotNull('progress')
            ->sortByDesc('date')
            ->sortByDesc('id')
            ->first();

        $avgProgress = $latestApproved !== null
            ? round((float) $latestApproved->progress, 1)
            : 0;

        $obstacleCount = $logbooks->filter(
            fn ($lb) => filled(trim($lb->obstacle ?? ''))
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Jumlah hari kerja (Senin–Jumat) di bulan tsb
        |--------------------------------------------------------------------------
        */

        $workdaysInMonth = 0;
        $startOfMonth    = \Carbon\Carbon::create($year, $month, 1);
        $endOfMonth      = $startOfMonth->copy()->endOfMonth();
        $cursor          = $startOfMonth->copy();

        while ($cursor->lte($endOfMonth)) {
            if ($cursor->isWeekday()) {
                $workdaysInMonth++;
            }
            $cursor->addDay();
        }

        /*
        |--------------------------------------------------------------------------
        | Bangun Ringkasan Otomatis (lokal, tanpa AI)
        |--------------------------------------------------------------------------
        */

        $autoSummary = $this->buildMonthlySummaryText(
            $logbooks,
            $total,
            $approved,
            $rejected,
            $approvalRate,
            $avgProgress,
            $obstacleCount,
            $workdaysInMonth
        );

        /*
        |--------------------------------------------------------------------------
        | Siapkan data JSON untuk modal (agar Blade tidak perlu parse closure)
        |--------------------------------------------------------------------------
        */

        $logbooksJson = $logbooks->map(function ($lb) {
            return [
                'id'       => $lb->id,
                'date'     => $lb->date?->format('d M Y'),
                'day'      => $lb->date?->translatedFormat('l'),
                'activity' => $lb->activity ?? '-',
                'obstacle' => filled(trim($lb->obstacle ?? ''))
                    ? $lb->obstacle
                    : null,
                'next_plan' => filled(trim($lb->next_plan ?? ''))
                    ? $lb->next_plan
                    : null,
                'progress'  => $lb->progress,
                'status'    => $lb->status,
                'documentation_image' => $lb->documentation_image
                    ? asset('storage/' . $lb->documentation_image)
                    : null,
                'documentation_images' => array_map(fn($img) => asset('storage/' . $img), $lb->documentation_images),
                'documentation_link' => $lb->documentation_link,
                'documentation_links' => $lb->documentation_links,
                'reviewer'    => $lb->reviewer?->name ?? null,
                'reviewed_at' => $lb->reviewed_at?->format('d M Y H:i') ?? null,
                'review_note' => filled(trim($lb->review_note ?? ''))
                    ? $lb->review_note
                    : null,
                'comments' => $lb->comments->map(function ($c) {
                    return [
                        'user'    => $c->user?->name ?? 'Anonim',
                        'body'    => $c->body ?? $c->comment ?? '',
                        'created' => $c->created_at?->format('d M Y H:i'),
                    ];
                })->values()->all(),
            ];
        })->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | Kirim ke View
        |--------------------------------------------------------------------------
        */

        return view('leader.logbooks.monthly-summary', [
            'user'            => $user,
            'logbooks'        => $logbooks,
            'logbooksJson'    => $logbooksJson,
            'total'           => $total,
            'approved'        => $approved,
            'rejected'        => $rejected,
            'approvalRate'    => $approvalRate,
            'avgProgress'     => $avgProgress,
            'obstacleCount'   => $obstacleCount,
            'workdaysInMonth' => $workdaysInMonth,
            'autoSummary'     => $autoSummary,
            'monthParam'      => $monthParam,
            'year'            => $year,
            'month'           => $month,
            'availableMonths' => $availableMonths,
        ]);
    }

    /**
     * Menampilkan detail satu logbook untuk Leader.
     */
    public function show(Logbook $logbook)
    {
        $logbook->load([
            'user.divisionRelation',
            'task.target',
            'task.assignedBy',
            'reviewer',
            'comments.user',
        ]);

        return view('leader.logbooks.show', compact('logbook'));
    }


    /**
     * Membangun ringkasan teks otomatis dari logbook sebulan.
     *
     * Tidak menggunakan AI. Semua diproses lokal dengan
     * teknik keyword frequency dan sentence extraction.
     */
    private function buildMonthlySummaryText(
        $logbooks,
        int $total,
        int $approved,
        int $rejected,
        int $approvalRate,
        float $avgProgress,
        int $obstacleCount,
        int $workdaysInMonth
    ): array {

        if ($total === 0) {
            return [];
        }

        /*
        |--------------------------------------------------------------------------
        | Konsistensi kehadiran
        |--------------------------------------------------------------------------
        */

        $attendanceRate = $workdaysInMonth > 0
            ? round(($total / $workdaysInMonth) * 100)
            : 0;

        $attendanceNote = match (true) {
            $attendanceRate >= 90 => 'Sangat konsisten mengisi logbook setiap hari kerja.',
            $attendanceRate >= 70 => 'Cukup konsisten mengisi logbook, namun masih ada beberapa hari yang terlewat.',
            $attendanceRate >= 50 => 'Pengisian logbook kurang konsisten. Beberapa hari kerja tidak tercatat.',
            default               => 'Tingkat pengisian logbook rendah. Banyak hari kerja tidak tercatat.',
        };

        /*
        |--------------------------------------------------------------------------
        | Performa approval
        |--------------------------------------------------------------------------
        */

        $approvalNote = match (true) {
            $approvalRate >= 90 => 'Performa sangat baik — hampir seluruh logbook disetujui.',
            $approvalRate >= 70 => 'Performa baik — sebagian besar logbook disetujui.',
            $approvalRate >= 50 => 'Performa cukup — masih terdapat beberapa logbook yang ditolak.',
            default             => 'Perlu perhatian — lebih dari setengah logbook ditolak.',
        };

        /*
        |--------------------------------------------------------------------------
        | Ekstrak kata kunci dari aktivitas (keyword frequency)
        |--------------------------------------------------------------------------
        */

        $stopWords = [
            'dan', 'atau', 'yang', 'di', 'ke', 'dari', 'dengan', 'untuk',
            'adalah', 'pada', 'ini', 'itu', 'ada', 'tidak', 'juga', 'sudah',
            'saya', 'kami', 'kita', 'bersama', 'dalam', 'oleh', 'karena',
            'serta', 'sebagai', 'akan', 'sudah', 'telah', 'sedang', 'lain',
            'sesuai', 'setelah', 'sebelum', 'lebih', 'dapat', 'masih', 'saat',
            'mulai', 'agar', 'supaya', 'belum', 'namun', 'tetapi', 'namun',
            'hal', 'cara', 'setiap', 'masing', 'antara', 'hingga', 'sampai',
        ];

        $wordFreq = [];

        foreach ($logbooks as $lb) {
            $text = strtolower(strip_tags($lb->activity ?? ''));
            $text = preg_replace('/[^a-z0-9\s]/u', ' ', $text);
            $words = array_filter(
                explode(' ', $text),
                fn ($w) => strlen($w) >= 4 && !in_array($w, $stopWords)
            );

            foreach ($words as $word) {
                $wordFreq[$word] = ($wordFreq[$word] ?? 0) + 1;
            }
        }

        arsort($wordFreq);
        $topKeywords = array_slice(array_keys($wordFreq), 0, 8);

        /*
        |--------------------------------------------------------------------------
        | Ekstrak kalimat aktivitas utama (top 5 kalimat pertama unik)
        |--------------------------------------------------------------------------
        */

        $activitySentences = [];
        $seen = [];

        foreach ($logbooks->where('status', 'approved') as $lb) {
            $text = trim($lb->activity ?? '');
            if ($text === '') continue;

            $firstLine = Str::limit(trim(explode("\n", $text)[0]), 120);

            // Hindari duplikat (similarity sederhana: 50 char pertama)
            $key = Str::limit($firstLine, 50);
            if (!in_array($key, $seen)) {
                $seen[]              = $key;
                $activitySentences[] = $firstLine;
            }

            if (count($activitySentences) >= 3) break;
        }

        /*
        |--------------------------------------------------------------------------
        | Ekstrak kendala
        |--------------------------------------------------------------------------
        */

        $obstacleList = [];
        foreach ($logbooks as $lb) {
            $obs = trim($lb->obstacle ?? '');
            if ($obs === '') continue;

            $firstObs = Str::limit(trim(explode("\n", $obs)[0]), 120);
            if (!in_array($firstObs, $obstacleList)) {
                $obstacleList[] = $firstObs;
            }

            if (count($obstacleList) >= 3) break;
        }

        /*
        |--------------------------------------------------------------------------
        | Progress note
        |--------------------------------------------------------------------------
        */

        $progressNote = match (true) {
            $avgProgress >= 80 => 'Progress pekerjaan berjalan sangat baik.',
            $avgProgress >= 60 => 'Progress pekerjaan berjalan dengan baik.',
            $avgProgress >= 40 => 'Progress pekerjaan masih perlu ditingkatkan.',
            $avgProgress > 0   => 'Progress pekerjaan masih rendah dan perlu perhatian.',
            default            => 'Belum ada data progress yang tercatat.',
        };

        return [
            'gambaran_umum'      => [
                'Karyawan mengisi logbook sebanyak ' . $total . ' kali dari ' . $workdaysInMonth . ' hari kerja (' . $attendanceRate . '%). ' . $attendanceNote,
                $approvalNote . ' Approval rate bulan ini: ' . $approvalRate . '%.',
            ],
            'aktivitas_utama'    => $activitySentences,
            'kata_kunci'         => $topKeywords,
            'kendala'            => $obstacleList,
            'progress_note'      => $progressNote . ' Rata-rata progress logbook approved: ' . $avgProgress . '%.',
            'catatan_pimpinan'   => $this->buildLeaderNote(
                $approvalRate,
                $attendanceRate,
                $obstacleCount,
                $avgProgress
            ),
        ];
    }


    /**
     * Buat catatan ringkas untuk pimpinan berdasarkan data statistik.
     */
    private function buildLeaderNote(
        int $approvalRate,
        int $attendanceRate,
        int $obstacleCount,
        float $avgProgress
    ): string {

        $notes = [];

        if ($approvalRate < 60) {
            $notes[] = 'Approval rate di bawah 60% — perlu dievaluasi kualitas pekerjaan.';
        }

        if ($obstacleCount >= 3) {
            $notes[] = 'Terdapat ' . $obstacleCount . ' kendala yang tercatat — perlu diketahui penyebabnya.';
        }

        if ($avgProgress < 50 && $avgProgress > 0) {
            $notes[] = 'Rata-rata progress di bawah 50% — pekerjaan perlu dipercepat.';
        }

        if (empty($notes)) {
            return 'Secara keseluruhan karyawan menunjukkan performa yang baik di bulan ini.';
        }

        return implode(' ', $notes);
    }


    /**
     * Fallback ringkasan lokal apabila AI tidak tersedia.
     */
    private function buildLocalSummary(
        User $user,
        $logbooks,
        int $approved,
        int $rejected,
        int $total,
        int $approvalRate,
        float $avgProgress
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Periode
        |--------------------------------------------------------------------------
        */

        $period =
            (
                $logbooks
                    ->last()
                    ?->date
                    ?->format(
                        'd M Y'
                    ) ?? '-'
            )
            .
            ' s/d '
            .
            (
                $logbooks
                    ->first()
                    ?->date
                    ?->format(
                        'd M Y'
                    ) ?? '-'
            );

        /*
        |--------------------------------------------------------------------------
        | Kendala
        |--------------------------------------------------------------------------
        */

        $obstacles =
            $logbooks
                ->filter(
                    fn ($lb) =>
                        filled(
                            trim(
                                $lb->obstacle ?? ''
                            )
                        )
                )
                ->count();

        /*
        |--------------------------------------------------------------------------
        | Aktivitas Terbaru
        |--------------------------------------------------------------------------
        */

        $activities =
            $logbooks
                ->take(5)
                ->map(
                    fn ($lb, $i) =>
                        ($i + 1)
                        . '. '
                        .trim($lb->activity ?? '-')
                )
                ->implode(
                    "\n"
                );

        /*
        |--------------------------------------------------------------------------
        | Ringkasan
        |--------------------------------------------------------------------------
        */

        $summary =
            "Ringkasan Logbook — {$user->name} ({$user->division})\n\n"

            .

            "Periode: {$period}\n\n"

            .

            "Gambaran Umum:\n"
            .
            "Terdapat {$total} logbook yang telah direview, "
            .
            "terdiri dari {$approved} logbook approved dan "
            .
            "{$rejected} logbook rejected. "
            .
            "Approval rate tercatat sebesar {$approvalRate}%.\n\n"

            .

            "Gambaran Progress:\n"
            .
            "Rata-rata progress pekerjaan dari logbook approved "
            .
            "adalah {$avgProgress}%.\n\n"

            .

            "Kendala:\n"
            .
            (
                $obstacles > 0
                    ? "Terdapat {$obstacles} logbook yang mencatat kendala."
                    : "Tidak terdapat kendala yang tercatat dalam logbook."
            )

            .

            "\n\nAktivitas Terbaru:\n"
            .
            $activities

            .

            "\n\n"

            .

            "[Ringkasan ini dibuat secara otomatis karena layanan AI tidak tersedia.]";

        return $summary;
    }
}