@extends('layouts.dashboard')

@section('title', 'Permintaan Barang - TSTH2')

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Permintaan Barang</h1>
        <p class="text-gray-500 font-medium">Kelola persetujuan pengeluaran stok barang</p>
    </div>

    <!-- Main Table Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Actions & Filters -->
        <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-4 bg-gray-50/50">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">🔍</span>
                    <input type="text" id="searchInput" placeholder="Cari pemohon atau barang..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-primary focus:border-primary transition-all text-sm shadow-sm font-medium">
                </div>
                <select id="filterStatus" class="bg-white border-gray-200 text-gray-700 rounded-lg text-sm focus:ring-primary focus:border-primary px-4 py-2.5 shadow-sm font-medium">
                    <option value="">Semua Status</option>
                    <option value="Waiting">Waiting</option>
                    <option value="On Progress">On Progress</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Ditunda">Ditunda</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>
            </div>
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600 whitespace-nowrap">
                <thead class="bg-gray-50/80 text-gray-900 font-bold border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4">ID Req</th>
                        <th class="px-6 py-4">Tanggal</th>
                        <th class="px-6 py-4">Pemohon</th>
                        <th class="px-6 py-4">Permintaan</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Keterangan / Jadwal</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50/80 transition-colors">
                        <td class="px-6 py-4 font-mono text-xs text-gray-500">#REQ{{ str_pad($req->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-6 py-4 font-medium text-gray-700">{{ $req->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            <strong class="text-gray-900 block text-base">{{ $req->user->name }}</strong>
                            <span class="text-xs text-gray-500">{{ $req->user->email }} ({{ ucfirst($req->user->divisi ?? '') }})</span>
                        </td>
                        <td class="px-6 py-4">
                            <strong class="text-gray-900 block">{{ $req->barang->nama_barang }}</strong>
                            <span class="text-xs font-bold text-accent">Jumlah: {{ $req->jumlah }} Unit</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($req->status == 'Waiting')
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-bold border border-amber-200 inline-flex">Waiting</span>
                            @elseif($req->status == 'On Progress')
                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-bold border border-blue-200 inline-flex">On Progress</span>
                            @elseif($req->status == 'Selesai')
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200 inline-flex">Selesai</span>
                            @elseif($req->status == 'Ditunda')
                                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold border border-orange-200 inline-flex">Ditunda</span>
                            @elseif($req->status == 'Dibatalkan')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold border border-red-200 inline-flex">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-normal min-w-[200px]">
                            @if($req->catatan)
                                <div class="mb-2 p-2 bg-amber-50 border border-amber-100 rounded-lg">
                                    <span class="text-xs font-bold text-amber-800 block mb-0.5">📝 Catatan:</span>
                                    <span class="text-amber-900 text-xs whitespace-pre-wrap">{{ $req->catatan }}</span>
                                </div>
                            @endif
                            @if($req->jadwal_pengambilan)
                                <div class="mb-2 p-2 bg-blue-50 border border-blue-100 rounded-lg">
                                    <span class="text-xs font-bold text-blue-800 block mb-0.5">⏰ Jadwal Ambil:</span>
                                    <span class="text-blue-900 font-medium text-sm">{{ \Carbon\Carbon::parse($req->jadwal_pengambilan)->format('d M Y, H:i') }}</span>
                                </div>
                            @endif
                            @if($req->pic_id)
                                <div class="mb-1 text-xs"><span class="font-bold text-gray-500">PIC:</span> <span class="text-gray-700">{{ $req->pic->name ?? '' }}</span></div>
                            @endif
                            @if($req->staff_gudang_id)
                                <div class="mb-1 text-xs"><span class="font-bold text-gray-500">Staff Gudang:</span> <span class="text-gray-700">{{ $req->staffGudang->name ?? '' }}</span></div>
                            @endif
                            @if($req->foto_pengambilan)
                                <div class="mt-2"><a href="{{ asset('storage/' . $req->foto_pengambilan) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1 bg-gray-100 hover:bg-gray-200 text-xs font-bold text-gray-700 rounded-lg transition-colors">📸 Lihat Bukti Foto</a></div>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 text-center">
                            @php
                                $role = auth()->user()->role;
                                $divisi = auth()->user()->divisi;
                            @endphp

                            {{-- Aksi PIC Gudang --}}
                            @if($role === 'pic_warehouse')
                                @if($req->status == 'Waiting')
                                    <button type="button" onclick="openModalPIC({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}', {{ $req->jumlah }}, false)" class="mb-1 w-full px-4 py-1.5 bg-primary text-white rounded-lg hover:bg-primary-light transition-colors font-bold text-xs shadow-md shadow-primary/20">
                                        Jadwal
                                    </button>
                                @elseif($req->status == 'Ditunda')
                                    <button type="button" onclick="openModalPIC({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}', {{ $req->jumlah }}, true)" class="mb-1 w-full px-4 py-1.5 bg-orange-500 text-white rounded-lg hover:bg-orange-600 transition-colors font-bold text-xs shadow-md shadow-orange-500/20">
                                        Reschedule
                                    </button>
                                @endif
                                <button type="button" onclick="openModalAssign({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}', '{{ $req->staff_gudang_id ?? '' }}')" class="mb-1 w-full px-4 py-1.5 bg-indigo-500 text-white rounded-lg hover:bg-indigo-600 transition-colors font-bold text-xs shadow-md shadow-indigo-500/20">
                                    👤 Assign
                                </button>
                            @endif

                            {{-- Aksi Staff Gudang --}}
                            @if($role === 'warehouse')
                                @if($req->status == 'On Progress')
                                    <button type="button" onclick="openModalGudang({{ $req->id }}, '{{ addslashes($req->user->name) }}', '{{ addslashes($req->barang->nama_barang) }}')" class="px-4 py-1.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-bold text-xs shadow-md shadow-emerald-500/20">
                                        Konfirmasi Selesai
                                    </button>
                                @else
                                    <span class="text-xs font-bold text-gray-400">-</span>
                                @endif
                            @endif

                            {{-- Aksi Staff Lapangan --}}
                            @if($role === 'agronom')
                                @if(in_array($req->status, ['Waiting', 'On Progress']))
                                    <form action="{{ route('request.status.update', $req->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan permintaan ini?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="px-4 py-1.5 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors font-bold text-xs shadow-md shadow-red-500/20">
                                            Batalkan
                                        </button>
                                    </form>
                                @else
                                    <span class="text-xs font-bold text-gray-400">-</span>
                                @endif
                            @endif

                            {{-- Admin Web --}}
                            @if($role === 'web_admin')
                                <span class="text-xs font-bold text-gray-400 block mb-1">Read Only</span>
                            @endif


                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400 font-medium">Belum ada riwayat permintaan barang.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Info -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <span class="text-sm text-gray-500 font-medium">Total <strong class="text-gray-900">{{ count($requests) }}</strong> permintaan</span>
        </div>
    </div>

    <!-- Modal PIC Gudang -->
    <div id="modalPIC" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900" id="picModalTitle">Tetapkan Jadwal Pengambilan</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal('modalPIC')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 grid gap-2 text-sm">
                    <div class="flex"><span class="w-24 font-bold text-gray-500">Pemohon</span> <strong id="pic_pemohon" class="text-gray-900"></strong></div>
                    <div class="flex"><span class="w-24 font-bold text-gray-500">Barang</span> <strong id="pic_barang" class="text-primary text-base"></strong></div>
                </div>

                <form id="formPIC" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="jadwal_pengambilan">Waktu Pengambilan (Batas Akhir) <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="jadwal_pengambilan" id="jadwal_pengambilan" class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium shadow-sm" required>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition-colors" onclick="closeModal('modalPIC')">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-primary hover:bg-primary-light rounded-lg font-bold transition-colors shadow-md shadow-primary/20">Simpan Jadwal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Staff Gudang -->
    <div id="modalGudang" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Konfirmasi Pengambilan Barang</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal('modalGudang')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-6 p-4 bg-emerald-50 rounded-xl border border-emerald-100 text-sm">
                    Pastikan Anda memberikan barang kepada <strong><span id="gudang_pemohon"></span></strong> untuk <strong><span id="gudang_barang"></span></strong>. Upload foto sebagai bukti serah terima.
                </div>

                <form id="formGudang" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="foto_pengambilan">Foto Bukti Penyerahan Barang <span class="text-red-500">*</span></label>
                        <div class="w-full relative border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:bg-gray-50 transition-colors">
                            <input type="file" name="foto_pengambilan" id="foto_pengambilan" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept="image/*" required>
                            <div class="text-gray-500">
                                <span class="text-3xl block mb-2">📸</span>
                                <span class="text-sm font-bold text-primary">Klik untuk memotret/unggah</span>
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF (Maks. 2MB)</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition-colors" onclick="closeModal('modalGudang')">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg font-bold transition-colors shadow-md shadow-emerald-500/20">Selesaikan Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Assign Staff (PIC Only) -->
    @if(auth()->user()->role === 'pic_warehouse')
    <div id="modalAssign" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4 opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform scale-95 transition-transform duration-300">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-900">Tugaskan ke Staff Warehouse</h2>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors" onclick="closeModal('modalAssign')">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <div class="p-6">
                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100 grid gap-2 text-sm">
                    <div class="flex"><span class="w-24 font-bold text-gray-500">Pemohon</span> <strong id="assign_pemohon" class="text-gray-900"></strong></div>
                    <div class="flex"><span class="w-24 font-bold text-gray-500">Barang</span> <strong id="assign_barang" class="text-primary text-base"></strong></div>
                </div>

                <form id="formAssign" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label class="block text-sm font-bold text-gray-700 mb-2" for="staff_gudang_id">Pilih Staff Warehouse</label>
                        <select name="staff_gudang_id" id="staff_gudang_id" class="w-full bg-white border border-gray-200 text-gray-900 rounded-lg focus:ring-primary focus:border-primary p-3 font-medium shadow-sm">
                            <option value="">-- Terbuka (Semua Staff Bisa Melihat) --</option>
                            @foreach($warehouseStaff ?? [] as $staff)
                                <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="px-4 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg font-bold transition-colors" onclick="closeModal('modalAssign')">Batal</button>
                        <button type="submit" class="px-6 py-2.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg font-bold transition-colors shadow-md shadow-indigo-500/20">Simpan Penugasan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif



@endsection

@push('scripts')
    <script>
        function openModalPIC(id, pemohon, barang, jumlah, isReschedule) {
            const modal = document.getElementById('modalPIC');
            document.getElementById('formPIC').action = `/request/${id}/status`;
            document.getElementById('pic_pemohon').textContent = pemohon;
            document.getElementById('pic_barang').textContent = barang + ` (${jumlah} Unit)`;
            
            document.getElementById('picModalTitle').textContent = isReschedule ? 'Jadwalkan Ulang Pengambilan' : 'Tetapkan Jadwal Pengambilan';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function openModalGudang(id, pemohon, barang) {
            const modal = document.getElementById('modalGudang');
            document.getElementById('formGudang').action = `/request/${id}/status`;
            document.getElementById('gudang_pemohon').textContent = pemohon;
            document.getElementById('gudang_barang').textContent = barang;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        function closeModal(modalId) {
            const modal = document.getElementById(modalId);
            modal.classList.add('opacity-0');
            modal.querySelector('.transform').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }, 300);
        }

        function openModalAssign(id, pemohon, barang, staff_id) {
            const modal = document.getElementById('modalAssign');
            document.getElementById('formAssign').action = `/request/${id}/assign`;
            document.getElementById('assign_pemohon').textContent = pemohon;
            document.getElementById('assign_barang').textContent = barang;
            document.getElementById('staff_gudang_id').value = staff_id || '';
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.querySelector('.transform').classList.remove('scale-95');
            }, 10);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const term = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('#tableBody tr');
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(term) ? '' : 'none';
                    });
                });
            }
            
            const filterStatus = document.getElementById('filterStatus');
            if (filterStatus) {
                filterStatus.addEventListener('change', function(e) {
                    const status = e.target.value;
                    const rows = document.querySelectorAll('#tableBody tr');
                    rows.forEach(row => {
                        if(status === '') {
                            row.style.display = '';
                        } else {
                            const text = row.querySelector('td:nth-child(5)').textContent.trim();
                            row.style.display = text === status ? '' : 'none';
                        }
                    });
                });
            }
        });
    </script>
@endpush
