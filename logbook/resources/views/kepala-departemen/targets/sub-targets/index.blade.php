<x-app-layout>

<x-slot name="header">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sub Target
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pembagian bobot dari Target utama.
            </p>
        </div>

        <a
            href="{{ route('kepala_departemen.targets.index') }}"
            class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 transition"
        >
            Kembali
        </a>
    </div>
</x-slot>


<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- ========================================================= --}}
        {{-- INFORMASI TARGET --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">

                <div class="min-w-0 flex-1">

                    {{-- Project --}}
                    <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">
                        Project
                    </p>

                    <h3 class="text-xl font-semibold text-gray-800">
                        {{ $target->project?->name ?? '-' }}
                    </h3>

                    @if($target->project?->description)
                        <p class="text-sm text-gray-500 mt-2">
                            {{ $target->project->description }}
                        </p>
                    @endif


                    {{-- Target --}}
                    <div class="mt-5">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-400 mb-1">
                            Target
                        </p>

                        <p class="text-lg font-semibold text-gray-800">
                            {{ $target->title }}
                        </p>

                        @if($target->description)
                            <p class="text-sm text-gray-500 mt-2">
                                {{ $target->description }}
                            </p>
                        @endif
                    </div>


                    {{-- Department & Division --}}
                    <div class="flex flex-wrap gap-x-6 gap-y-2 mt-5">

                        @if($target->department)
                            <p class="text-sm text-gray-500">
                                Department:
                                <span class="font-medium text-gray-700">
                                    {{ $target->department->name }}
                                </span>
                            </p>
                        @endif

                        @if($target->division)
                            <p class="text-sm text-gray-500">
                                Divisi:
                                <span class="font-medium text-gray-700">
                                    {{ $target->division->name }}
                                </span>
                            </p>
                        @endif

                    </div>

                </div>


                {{-- Status Target --}}
                <div class="shrink-0">

                    @if($target->status === 'active')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            Active
                        </span>

                    @elseif($target->status === 'pending')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700">
                            Pending
                        </span>

                    @elseif($target->status === 'completed')

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                            Completed
                        </span>

                    @else

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                            Inactive
                        </span>

                    @endif

                </div>

            </div>


            {{-- ===================================================== --}}
            {{-- RINGKASAN BOBOT --}}
            {{-- ===================================================== --}}
            <div class="mt-6 pt-6 border-t border-gray-100">

                <div class="flex items-center justify-between mb-2">

                    <div>
                        <p class="text-sm font-medium text-gray-700">
                            Alokasi Bobot Sub Target
                        </p>

                        <p class="text-xs text-gray-500 mt-1">
                            Total bobot seluruh Sub Target harus mencapai 100%.
                        </p>
                    </div>

                    <span class="text-sm font-bold text-gray-700">
                        {{ $allocatedPercentage }} / 100%
                    </span>

                </div>


                {{-- Progress Bar --}}
                <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">

                    <div
                        class="h-full rounded-full transition-all
                            {{ $allocatedPercentage >= 100
                                ? 'bg-emerald-500'
                                : 'bg-blue-500' }}"
                        style="width: {{ min($allocatedPercentage, 100) }}%"
                    ></div>

                </div>


                {{-- Statistik --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">

                    {{-- Teralokasi --}}
                    <div class="bg-blue-50 rounded-xl p-4">

                        <p class="text-xs text-blue-600">
                            Bobot Teralokasi
                        </p>

                        <p class="text-xl font-bold text-blue-700 mt-1">
                            {{ $allocatedPercentage }}%
                        </p>

                    </div>


                    {{-- Sisa --}}
                    <div class="bg-gray-50 rounded-xl p-4">

                        <p class="text-xs text-gray-500">
                            Sisa Bobot
                        </p>

                        <p class="text-xl font-bold text-gray-700 mt-1">
                            {{ $remainingPercentage }}%
                        </p>

                    </div>


                    {{-- Status --}}
                    <div
                        class="rounded-xl p-4
                            {{ $allocatedPercentage == 100
                                ? 'bg-emerald-50'
                                : ($allocatedPercentage > 100
                                    ? 'bg-red-50'
                                    : 'bg-amber-50') }}"
                    >

                        <p
                            class="text-xs
                                {{ $allocatedPercentage == 100
                                    ? 'text-emerald-600'
                                    : ($allocatedPercentage > 100
                                        ? 'text-red-600'
                                        : 'text-amber-600') }}"
                        >
                            Status Alokasi
                        </p>

                        <p
                            class="text-sm font-bold mt-2
                                {{ $allocatedPercentage == 100
                                    ? 'text-emerald-700'
                                    : ($allocatedPercentage > 100
                                        ? 'text-red-700'
                                        : 'text-amber-700') }}"
                        >

                            @if($allocatedPercentage == 100)

                                Bobot Lengkap

                            @elseif($allocatedPercentage > 100)

                                Melebihi 100%

                            @else

                                Belum Lengkap

                            @endif

                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- ========================================================= --}}
        {{-- DAFTAR SUB TARGET --}}
        {{-- ========================================================= --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

            <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>

                    <h3 class="font-semibold text-gray-800">
                        Daftar Sub Target
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Bagian pekerjaan yang membentuk Target.
                    </p>

                </div>


                {{-- Tambah --}}
                @if($remainingPercentage > 0)

                    <a
                        href="{{ route('kepala_departemen.targets.sub-targets.create', $target) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition"
                    >
                        <span class="text-lg leading-none">+</span>
                        Tambah Sub Target
                    </a>

                @else

                    <span class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-100 text-gray-500 text-sm font-medium">
                        Bobot Sudah 100%
                    </span>

                @endif

            </div>


            {{-- ===================================================== --}}
            {{-- SUCCESS --}}
            {{-- ===================================================== --}}
            @if(session('success'))

                <div class="mx-6 mt-5 px-4 py-3 rounded-lg bg-emerald-50 border border-emerald-100 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- ERROR --}}
            {{-- ===================================================== --}}
            @if(session('error'))

                <div class="mx-6 mt-5 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-sm text-red-700">
                    {{ session('error') }}
                </div>

            @endif


            @if($errors->any())

                <div class="mx-6 mt-5 px-4 py-3 rounded-lg bg-red-50 border border-red-100 text-sm text-red-700">

                    <p class="font-medium mb-1">
                        Terjadi kesalahan:
                    </p>

                    <ul class="list-disc list-inside space-y-1">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- ===================================================== --}}
            {{-- LIST SUB TARGET --}}
            {{-- ===================================================== --}}
            @if($target->subTargets->count())

                <div class="divide-y divide-gray-100">

                    @foreach($target->subTargets as $subTarget)

                        <div class="px-6 py-5">

                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                                {{-- Informasi --}}
                                <div class="min-w-0 flex-1">

                                    <div class="flex items-start gap-3">

                                        {{-- Nomor --}}
                                        <div class="shrink-0 w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center font-semibold text-sm">
                                            {{ $loop->iteration }}
                                        </div>


                                        <div class="min-w-0">

                                            <h4 class="font-semibold text-gray-800">
                                                {{ $subTarget->title }}
                                            </h4>


                                            @if($subTarget->description)

                                                <p class="text-sm text-gray-500 mt-1">
                                                    {{ $subTarget->description }}
                                                </p>

                                            @endif


                                            {{-- Periode --}}
                                            <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-gray-500">

                                                <span class="font-medium text-gray-600">
                                                    Periode:
                                                </span>

                                                @if($subTarget->period_start)

                                                    <span>
                                                        {{ $subTarget->period_start->format('d M Y') }}
                                                    </span>

                                                @else

                                                    <span>-</span>

                                                @endif

                                                <span>—</span>

                                                @if($subTarget->period_end)

                                                    <span>
                                                        {{ $subTarget->period_end->format('d M Y') }}
                                                    </span>

                                                @else

                                                    <span>-</span>

                                                @endif

                                            </div>


                                            {{-- Progress --}}
                                            <div class="mt-4 max-w-xl">

                                                <div class="flex items-center justify-between mb-1">

                                                    <span class="text-xs text-gray-500">
                                                        Progress
                                                    </span>

                                                    <span class="text-xs font-semibold text-gray-700">
                                                        {{ $subTarget->progress ?? 0 }}%
                                                    </span>

                                                </div>

                                                <div class="w-full h-2 bg-gray-100 rounded-full overflow-hidden">

                                                    <div
                                                        class="h-full bg-blue-500 rounded-full"
                                                        style="width: {{ min($subTarget->progress ?? 0, 100) }}%"
                                                    ></div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- Bobot & Aksi --}}
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4 lg:shrink-0">

                                    <div class="text-left sm:text-right">

                                        <p class="text-xs text-gray-500 mb-1">
                                            Bobot
                                        </p>

                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 text-blue-600 text-sm font-semibold">
                                            {{ $subTarget->percentage }}%
                                        </span>

                                    </div>


                                    <div class="flex items-center gap-2">

                                        <a
                                            href="{{ route('kepala_departemen.targets.sub-targets.edit', [$target, $subTarget]) }}"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-gray-200 transition"
                                        >
                                            Edit
                                        </a>


                                        <button
                                            type="button"
                                            onclick="openDeleteModal(
                                                '{{ $subTarget->id }}',
                                                @js($subTarget->title)
                                            )"
                                            class="inline-flex items-center px-3 py-2 rounded-lg bg-red-50 text-red-600 text-sm font-medium hover:bg-red-100 transition"
                                        >
                                            Hapus
                                        </button>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            @else

                {{-- Empty State --}}
                <div class="px-6 py-12 text-center">

                    <div class="w-12 h-12 mx-auto rounded-full bg-gray-100 flex items-center justify-center mb-4">

                        <span class="text-xl font-semibold text-gray-400">
                            %
                        </span>

                    </div>

                    <h4 class="font-medium text-gray-700">
                        Belum ada Sub Target
                    </h4>

                    <p class="text-sm text-gray-500 mt-1 max-w-md mx-auto">
                        Target ini belum memiliki pembagian Sub Target.
                        Tambahkan Sub Target untuk menentukan bobot pekerjaan.
                    </p>


                    @if($remainingPercentage > 0)

                        <a
                            href="{{ route('kepala_departemen.targets.sub-targets.create', $target) }}"
                            class="inline-flex items-center gap-2 mt-5 px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-medium hover:bg-emerald-700 transition"
                        >
                            + Tambah Sub Target
                        </a>

                    @endif

                </div>

            @endif

        </div>

    </div>
</div>


{{-- ================================================================ --}}
{{-- MODAL HAPUS --}}
{{-- ================================================================ --}}
<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden"
    aria-labelledby="deleteModalTitle"
    role="dialog"
    aria-modal="true"
>

    <div
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"
        onclick="closeDeleteModal()"
    ></div>


    <div class="relative flex min-h-full items-center justify-center px-4 py-8">

        <div
            class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
            onclick="event.stopPropagation()"
        >

            <div class="p-6">

                <div class="flex justify-center mb-4">

                    <div class="w-14 h-14 rounded-full bg-red-50 flex items-center justify-center">

                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-7 h-7 text-red-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            stroke-width="2"
                        >

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13a2 2 0 001.71 2.64h15.64a2 2 0 001.71-2.64l-7.82-13a2 2 0 00-3.42 0z"
                            />

                        </svg>

                    </div>

                </div>


                <div class="text-center">

                    <h3
                        id="deleteModalTitle"
                        class="text-lg font-semibold text-gray-800"
                    >
                        Hapus Sub Target?
                    </h3>

                    <p class="text-sm text-gray-500 mt-2">

                        Apakah kamu yakin ingin menghapus

                        <span
                            id="deleteSubTargetName"
                            class="font-semibold text-gray-700"
                        ></span>

                        ?

                    </p>

                </div>

            </div>


            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex items-center justify-end gap-3">

                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 text-sm font-medium hover:bg-gray-100 transition"
                >
                    Batal
                </button>


                <form
                    id="deleteSubTargetForm"
                    method="POST"
                >

                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-red-600 text-white text-sm font-medium hover:bg-red-700 transition"
                    >
                        Ya, Hapus
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>


{{-- ================================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ================================================================ --}}
<script>

    function openDeleteModal(id, title) {

        const modal = document.getElementById('deleteModal');
        const name = document.getElementById('deleteSubTargetName');
        const form = document.getElementById('deleteSubTargetForm');

        name.textContent = title;

        form.action =
            "{{ url('/kepala_departemen/targets/' . $target->id . '/sub-targets') }}/" + id;

        modal.classList.remove('hidden');

        document.body.classList.add('overflow-hidden');
    }


    function closeDeleteModal() {

        const modal = document.getElementById('deleteModal');

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');
    }


    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {

            closeDeleteModal();

        }

    });

</script>

</x-app-layout>
