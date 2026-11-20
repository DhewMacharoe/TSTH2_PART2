@php
    use Carbon\Carbon;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    $user = auth()->user();
    $notifications = [];
    $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');

    try {
        if ($user && Schema::hasTable('permintaan_barangs')) {
            $pending = DB::table('permintaan_barangs')
                ->leftJoin('barangs', 'permintaan_barangs.barang_id', '=', 'barangs.id')
                ->leftJoin('users as pemohon', 'permintaan_barangs.user_id', '=', 'pemohon.id')
                ->where('permintaan_barangs.status', 'Menunggu Approval')
                ->select(
                    'permintaan_barangs.*',
                    'barangs.nama_barang',
                    'pemohon.name as pemohon_name'
                )
                ->latest('permintaan_barangs.created_at')
                ->get();

            $userDivName = '';
            if (!empty($user->division_id) && Schema::hasTable('divisions')) {
                $div = DB::table('divisions')->where('id', $user->division_id)->first();
                if ($div) $userDivName = strtolower($div->name);
            }

            $userDeptName = '';
            if (!empty($user->department_id) && Schema::hasTable('departments')) {
                $dept = DB::table('departments')->where('id', $user->department_id)->first();
                if ($dept) $userDeptName = strtolower($dept->name);
            }

            foreach ($pending as $p) {
                $createdAt = Carbon::parse($p->created_at);
                $hours = $createdAt->diffInHours(now());
                $days = $createdAt->diffInDays(now());
                $isGA = ($p->jenis_barang === 'lapangan'); // General Affair
                $isRdI = ($p->jenis_barang === 'lab');     // Riset dan Inovasi

                $namaBarang = $p->nama_barang ?? ('Barang #' . $p->barang_id);
                $pemohon = $p->pemohon_name ?? 'Karyawan';
                $qty = $p->jumlah . ' ' . $p->satuan_diminta;

                // 1. ESKALASI 7x24 JAM (>= 168 JAM) -> KEPALA DEPARTEMEN & PIMPINAN
                if ($hours >= 168) {
                    $shouldAdd = false;
                    if (in_array($user->role, ['pimpinan', 'web_admin', 'admin'])) {
                        $shouldAdd = true;
                    } elseif ($user->role === 'kepala_departemen') {
                        if ($isGA && ($user->department_id == 1 || str_contains($userDeptName, 'general') || empty($user->department_id))) {
                            $shouldAdd = true;
                        } elseif ($isRdI && ($user->department_id == 2 || str_contains($userDeptName, 'riset') || empty($user->department_id))) {
                            $shouldAdd = true;
                        }
                    } elseif ($user->role === 'kepala_divisi') {
                        if ($isGA && ($user->department_id == 1 || str_contains($userDivName, 'administrasi') || empty($user->department_id))) {
                            $shouldAdd = true;
                        } elseif ($isRdI && ($user->department_id == 2 || str_contains($userDivName, 'genomic') || empty($user->department_id))) {
                            $shouldAdd = true;
                        }
                    } elseif (($isGA && $user->role === 'pic_gudang') || ($isRdI && $user->role === 'pic_lab')) {
                        $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $notifications[] = [
                            'id' => $p->id,
                            'level' => 'kadep',
                            'urgency' => 'critical',
                            'badge' => 'Eskalasi Kadep (>7 Hari)',
                            'badge_color' => 'bg-red-100 text-red-800 border-red-200',
                            'icon' => 'warning',
                            'icon_color' => 'text-red-600 bg-red-100',
                            'card_bg' => 'bg-red-50/50 border-l-4 border-l-red-500',
                            'title' => "Permintaan Belum Diproses ({$days} Hari)",
                            'barang' => $namaBarang,
                            'qty' => $qty,
                            'pemohon' => $pemohon,
                            'kategori' => $isGA ? 'Gudang Lapangan (GA)' : 'Laboratorium (RdI)',
                            'message' => "Permintaan belum ditangani tim selama {$days} hari. Mohon pimpinan / kepala departemen mengingatkan bawahan terkait.",
                            'time_ago' => $createdAt->diffForHumans(),
                        ];
                    }
                }
                // 2. ESKALASI 3x24 JAM (>= 72 JAM) -> KEPALA DIVISI (ADMINISTRASI UNTUK GA, GENOMIC UNTUK RDI) & PIMPINAN
                elseif ($hours >= 72) {
                    $shouldAdd = false;
                    $targetKadiv = $isGA ? 'Divisi Administrasi (GA)' : 'Divisi Genomic (RdI)';

                    if (in_array($user->role, ['pimpinan', 'web_admin', 'admin'])) {
                        $shouldAdd = true;
                    } elseif ($user->role === 'kepala_divisi') {
                        if ($isGA && ($user->department_id == 1 || str_contains($userDivName, 'administrasi') || empty($user->division_id))) {
                            $shouldAdd = true;
                        } elseif ($isRdI && ($user->department_id == 2 || str_contains($userDivName, 'genomic') || empty($user->division_id))) {
                            $shouldAdd = true;
                        }
                    } elseif (($isGA && $user->role === 'pic_gudang') || ($isRdI && $user->role === 'pic_lab')) {
                        $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $notifications[] = [
                            'id' => $p->id,
                            'level' => 'kadiv',
                            'urgency' => 'high',
                            'badge' => 'Perhatian Kadiv (>3 Hari)',
                            'badge_color' => 'bg-amber-100 text-amber-800 border-amber-200',
                            'icon' => 'schedule',
                            'icon_color' => 'text-amber-600 bg-amber-100',
                            'card_bg' => 'bg-amber-50/50 border-l-4 border-l-amber-500',
                            'title' => "Permintaan Belum Diproses PIC ({$days} Hari)",
                            'barang' => $namaBarang,
                            'qty' => $qty,
                            'pemohon' => $pemohon,
                            'kategori' => $isGA ? 'Gudang Lapangan (GA)' : 'Laboratorium (RdI)',
                            'message' => "Permintaan belum ditangani PIC selama {$days} hari. Masuk ke notifikasi {$targetKadiv} untuk mengingatkan PIC.",
                            'time_ago' => $createdAt->diffForHumans(),
                        ];
                    }
                }
                // 3. ESKALASI 1x24 JAM (>= 24 JAM) -> PIC GUDANG / LAB
                elseif ($hours >= 24) {
                    $shouldAdd = false;
                    if (($isGA && $user->role === 'pic_gudang') || ($isRdI && $user->role === 'pic_lab') || $user->role === 'web_admin') {
                        $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $notifications[] = [
                            'id' => $p->id,
                            'level' => 'pic',
                            'urgency' => 'medium',
                            'badge' => 'Perlu Tindakan PIC (>24 Jam)',
                            'badge_color' => 'bg-orange-100 text-orange-800 border-orange-200',
                            'icon' => 'pending_actions',
                            'icon_color' => 'text-orange-600 bg-orange-100',
                            'card_bg' => 'bg-orange-50/50 border-l-4 border-l-orange-400',
                            'title' => "Menunggu Tindakan PIC ({$hours} Jam)",
                            'barang' => $namaBarang,
                            'qty' => $qty,
                            'pemohon' => $pemohon,
                            'kategori' => $isGA ? 'Gudang Lapangan (GA)' : 'Laboratorium (RdI)',
                            'message' => "Permintaan menunggu approval / verifikasi Anda sejak lebih dari 24 jam lalu.",
                            'time_ago' => $createdAt->diffForHumans(),
                        ];
                    }
                }
                // 4. BARU (< 24 JAM) -> PIC GUDANG / LAB
                else {
                    $shouldAdd = false;
                    if (($isGA && $user->role === 'pic_gudang') || ($isRdI && $user->role === 'pic_lab') || $user->role === 'web_admin') {
                        $shouldAdd = true;
                    }

                    if ($shouldAdd) {
                        $notifications[] = [
                            'id' => $p->id,
                            'level' => 'baru',
                            'urgency' => 'normal',
                            'badge' => 'Permintaan Baru',
                            'badge_color' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'icon' => 'inbox',
                            'icon_color' => 'text-blue-600 bg-blue-100',
                            'card_bg' => 'bg-blue-50/30 border-l-4 border-l-blue-400',
                            'title' => "Permintaan Baru Masuk",
                            'barang' => $namaBarang,
                            'qty' => $qty,
                            'pemohon' => $pemohon,
                            'kategori' => $isGA ? 'Gudang Lapangan (GA)' : 'Laboratorium (RdI)',
                            'message' => "Permintaan baru menunggu tindakan dari Anda.",
                            'time_ago' => $createdAt->diffForHumans(),
                        ];
                    }
                }
            }

            // UNTUK PEMOHON BIASA: Notifikasi jika status permintaannya disetujui / siap diambil
            if (!in_array($user->role, ['pic_gudang', 'pic_lab', 'pimpinan', 'kepala_departemen', 'kepala_divisi', 'web_admin'])) {
                $myRequests = DB::table('permintaan_barangs')
                    ->leftJoin('barangs', 'permintaan_barangs.barang_id', '=', 'barangs.id')
                    ->where('permintaan_barangs.user_id', $user->id)
                    ->whereIn('permintaan_barangs.status', ['Menunggu Pengambilan', 'Pending (Jadwal Ulang)', 'Disetujui', 'Barang Diambil'])
                    ->select('permintaan_barangs.*', 'barangs.nama_barang')
                    ->latest('permintaan_barangs.updated_at')
                    ->take(5)
                    ->get();

                foreach ($myRequests as $mr) {
                    $namaBarang = $mr->nama_barang ?? ('Barang #' . $mr->barang_id);
                    $notifications[] = [
                        'id' => $mr->id,
                        'level' => 'pemohon',
                        'urgency' => 'normal',
                        'badge' => $mr->status,
                        'badge_color' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'icon' => 'check_circle',
                        'icon_color' => 'text-emerald-600 bg-emerald-100',
                        'card_bg' => 'bg-emerald-50/30 border-l-4 border-l-emerald-400',
                        'title' => "Status: {$mr->status}",
                        'barang' => $namaBarang,
                        'qty' => $mr->jumlah . ' ' . $mr->satuan_diminta,
                        'pemohon' => 'Saya',
                        'kategori' => $mr->jenis_barang === 'lapangan' ? 'Gudang Lapangan' : 'Laboratorium',
                        'message' => "Permintaan {$namaBarang} ({$mr->jumlah} {$mr->satuan_diminta}) saat ini berstatus: {$mr->status}.",
                        'time_ago' => Carbon::parse($mr->updated_at)->diffForHumans(),
                    ];
                }
            }
        }
    } catch (\Throwable $e) {}

    $notifCount = count($notifications);
@endphp

{{-- Notifikasi Dropdown Komponen Interaktif (Tidak Redirect Langsung) --}}
<div class="relative" x-data="{ notifOpen: false }" @click.away="notifOpen = false">
    {{-- Tombol Lonceng --}}
    <button 
        @click="notifOpen = !notifOpen" 
        type="button" 
        class="relative w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary/20" 
        title="Buka Notifikasi"
        aria-label="Notifikasi"
    >
        <span class="material-symbols-rounded" style="font-size:20px;">notifications</span>
        @if($notifCount > 0)
            <span class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 bg-red-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center shadow-md animate-pulse">
                {{ $notifCount > 9 ? '9+' : $notifCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown Popover --}}
    <div 
        x-show="notifOpen" 
        x-transition:enter="transition ease-out duration-150" 
        x-transition:enter-start="opacity-0 scale-95 translate-y-1" 
        x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
        x-transition:leave="transition ease-in duration-100" 
        x-transition:leave-start="opacity-100 scale-100 translate-y-0" 
        x-transition:leave-end="opacity-0 scale-95 translate-y-1" 
        class="absolute right-0 mt-2 w-80 sm:w-96 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden" 
        style="display:none;"
    >
        {{-- Header Dropdown --}}
        <div class="px-5 py-3.5 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="material-symbols-rounded text-primary text-xl">notifications_active</span>
                <h3 class="font-bold text-gray-900 text-sm">Notifikasi & Eskalasi</h3>
            </div>
            @if($notifCount > 0)
                <span class="text-[11px] font-extrabold bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                    {{ $notifCount }} Perlu Perhatian
                </span>
            @else
                <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    Tertangani
                </span>
            @endif
        </div>

        {{-- Daftar Notifikasi --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 custom-scrollbar">
            @forelse($notifications as $n)
                <div class="p-4 hover:bg-gray-50/80 transition-colors {{ $n['card_bg'] }}">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $n['icon_color'] }} flex items-center justify-center shrink-0 mt-0.5">
                            <span class="material-symbols-rounded" style="font-size:18px;">{{ $n['icon'] }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <span class="text-[10px] font-extrabold px-1.5 py-0.5 rounded border {{ $n['badge_color'] }}">
                                    {{ $n['badge'] }}
                                </span>
                                <span class="text-[10px] text-gray-400 font-medium shrink-0">
                                    {{ $n['time_ago'] }}
                                </span>
                            </div>

                            <p class="text-xs font-bold text-gray-900 leading-snug">
                                {{ $n['barang'] }} <span class="text-gray-500 font-normal">({{ $n['qty'] }})</span>
                            </p>

                            <p class="text-[11px] text-gray-500 mt-0.5">
                                Pemohon: <strong class="text-gray-700">{{ $n['pemohon'] }}</strong> &bull; <span class="text-primary font-medium">{{ $n['kategori'] }}</span>
                            </p>

                            <p class="text-[11px] text-gray-600 mt-1 leading-relaxed bg-white/70 p-1.5 rounded-lg border border-gray-100">
                                {{ $n['message'] }}
                            </p>

                            <div class="mt-2 flex items-center justify-end">
                                <a href="{{ $urlGudang }}/permintaan" class="inline-flex items-center gap-1 px-2.5 py-1 bg-white hover:bg-gray-100 text-primary border border-gray-200 rounded-lg text-[10px] font-bold shadow-sm transition-all group">
                                    <span>Tinjau Permintaan</span>
                                    <span class="material-symbols-rounded text-xs group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-10 px-6 text-center text-gray-400">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center mb-2">
                        <span class="material-symbols-rounded text-2xl">check_circle</span>
                    </div>
                    <p class="text-xs font-bold text-gray-700">Semua Permintaan Tertangani</p>
                    <p class="text-[11px] text-gray-400 mt-0.5">Tidak ada permintaan tertunda yang membutuhkan eskalasi saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>
</div>
