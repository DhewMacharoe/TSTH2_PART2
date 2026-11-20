@extends('layouts.admin')

@section('title', 'Edit Tanaman | Admin TSTH2')

@section('content')
    <a href="{{ route('admin.plants.index') }}" class="text-xs font-bold text-[#00634e]">← Kembali ke koleksi</a>
    <div class="mt-5 max-w-4xl"><p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">Koleksi tanaman</p><h1 class="mt-2 font-['Manrope'] text-3xl font-extrabold text-[#003f32]">Edit {{ $plant->name }}</h1></div>
    <form method="POST" action="{{ route('admin.plants.update', $plant) }}" enctype="multipart/form-data" novalidate data-swal-validation class="mt-7 rounded-xl border border-[#dbe9dd] bg-white p-6 shadow-[0_5px_18px_rgba(0,63,50,.05)] sm:p-8">@csrf @method('PUT') @php($submitLabel = 'Simpan Perubahan') @include('admin.plants._form')</form>

    <section id="qr-code" class="mt-6 rounded-xl border border-[#dbe9dd] bg-white p-6 shadow-[0_5px_18px_rgba(0,63,50,.05)] sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-[.18em] text-[#b87900]">Identitas publik</p>
                <h2 class="mt-2 text-xl font-extrabold text-[#003f32]">QR Code tanaman</h2>
                <p class="mt-1 max-w-xl text-xs leading-5 text-[#718178]">QR Code ini permanen dan tetap mengarah ke halaman detail meskipun data tanaman diperbarui.</p>
                <span class="mt-3 inline-flex items-center gap-2 rounded-full bg-[#e8f3df] px-3 py-1 text-[10px] font-bold text-[#28604d]">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#3b8b61]"></span> QR Code tersedia
                </span>
            </div>
            <div class="flex shrink-0 flex-col items-center gap-4">
                <div class="rounded-xl border border-[#dbe9dd] bg-white p-3">
                    <img src="{{ route('admin.plants.qr', $plant) }}" alt="QR Code {{ $plant->name }}" class="h-44 w-44 sm:h-52 sm:w-52">
                </div>
                <div class="flex flex-wrap justify-center gap-2">
                    <a href="{{ route('admin.plants.qr.download', $plant) }}" class="rounded-lg bg-[#003f32] px-4 py-2.5 text-xs font-bold text-white transition hover:bg-[#00634e]">Unduh QR Code</a>
                    <a href="{{ route('admin.plants.qr.print', $plant) }}" target="_blank" rel="noopener" class="rounded-lg border border-[#b8d1bd] px-4 py-2.5 text-xs font-bold text-[#00634e] transition hover:bg-[#eef6ef]">Cetak QR Code</a>
                </div>
            </div>
        </div>
    </section>
@endsection
