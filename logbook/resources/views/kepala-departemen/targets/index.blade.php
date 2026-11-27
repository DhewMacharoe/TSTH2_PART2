<x-app-layout>

<div class="min-h-screen bg-gray-50 py-8">

    <div class="max-w-7xl mx-auto px-6">

        {{-- ===================================================== --}}
        {{-- HEADER --}}
        {{-- ===================================================== --}}

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">

            <div>

                <h1 class="text-2xl font-bold text-gray-800">
                    Target Departemen
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Daftar Target yang menjadi acuan Kepala Divisi
                    dalam menentukan pekerjaan.
                </p>

            </div>


            <a
                href="{{ route('kepala_departemen.targets.create') }}"
                class="inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition"
            >
                + Tambah Target
            </a>

        </div>


        {{-- ===================================================== --}}
        {{-- SUCCESS MESSAGE --}}
        {{-- ===================================================== --}}

        @if (session('success'))

            <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700">

                {{ session('success') }}

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- ERROR MESSAGE --}}
        {{-- ===================================================== --}}

        @if (session('error'))

            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700">

                {{ session('error') }}

            </div>

        @endif


        {{-- ===================================================== --}}
        {{-- TARGET LIST --}}
        {{-- ===================================================== --}}

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">

            <div class="px-6 py-5 border-b border-gray-200">

                <h2 class="text-lg font-semibold text-gray-800">
                    Daftar Target
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Target merupakan bagian pekerjaan divisi
                    dalam suatu Project dan menjadi acuan Kepala Divisi
                    dalam menentukan pekerjaan untuk karyawan.
                </p>

            </div>


            {{-- ================================================= --}}
            {{-- TABLE --}}
            {{-- ================================================= --}}

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50">

                        <tr>

                            {{-- PROJECT --}}

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Project
                            </th>


                            {{-- TARGET --}}

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Target
                            </th>


                            {{-- DIVISION --}}

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Divisi
                            </th>


                            {{-- DEPARTMENT --}}

                            @if (auth()->user()->role === 'kepala_departemen')

                                <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                    Departemen
                                </th>

                            @endif


                            {{-- PERIOD --}}

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Periode
                            </th>


                            {{-- STATUS --}}

                            <th class="px-6 py-4 text-left font-semibold text-gray-600">
                                Status
                            </th>


                            {{-- ACTIONS --}}

                            <th class="px-6 py-4 text-right font-semibold text-gray-600">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-200">

                        @forelse ($targets as $target)

                            <tr class="hover:bg-gray-50 transition">


                                {{-- ================================================= --}}
                                {{-- PROJECT --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 align-top">

                                    @if ($target->project)

                                        <div class="font-semibold text-indigo-700">

                                            {{ $target->project->name }}

                                        </div>

                                        @if ($target->project->description)

                                            <div class="text-xs text-gray-500 mt-1 max-w-xs">

                                                {{ \Illuminate\Support\Str::limit(
                                                    $target->project->description,
                                                    80
                                                ) }}

                                            </div>

                                        @endif

                                        <div class="mt-2">

                                            @if ($target->project->status === 'active')

                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-emerald-50 text-emerald-700">
                                                    Active
                                                </span>

                                            @elseif ($target->project->status === 'pending')

                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-amber-50 text-amber-700">
                                                    Pending
                                                </span>

                                            @elseif ($target->project->status === 'completed')

                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-blue-50 text-blue-700">
                                                    Completed
                                                </span>

                                            @else

                                                <span class="inline-flex px-2 py-0.5 rounded-full text-[11px] font-medium bg-gray-100 text-gray-600">
                                                    Inactive
                                                </span>

                                            @endif

                                        </div>

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- TARGET --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 align-top">

                                    <div class="font-semibold text-gray-800">

                                        {{ $target->title }}

                                    </div>


                                    @if ($target->description)

                                        <div class="text-xs text-gray-500 mt-1 max-w-md">

                                            {{ $target->description }}

                                        </div>

                                    @endif


                                    <div class="text-xs text-gray-400 mt-2">

                                        {{ $target->subTargets->count() }}

                                        Sub Target

                                    </div>

                                </td>


                                {{-- ================================================= --}}
                                {{-- DIVISION --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 align-top">

                                    @if ($target->division)

                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">

                                            {{ $target->division->name }}

                                        </span>

                                    @else

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- DEPARTMENT --}}
                                {{-- ================================================= --}}

                                @if (auth()->user()->role === 'kepala_departemen')

                                    <td class="px-6 py-5 text-gray-600 align-top">

                                        {{ $target->department?->name ?? '-' }}

                                    </td>

                                @endif


                                {{-- ================================================= --}}
                                {{-- PERIOD --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 text-gray-600 whitespace-nowrap align-top">

                                    @if ($target->period_start && $target->period_end)

                                        {{ $target->period_start->format('d M Y') }}

                                        <span class="text-gray-400">
                                            -
                                        </span>

                                        {{ $target->period_end->format('d M Y') }}

                                    @elseif ($target->period_start)

                                        {{ $target->period_start->format('d M Y') }}

                                        <span class="text-gray-400">
                                            - Tidak ditentukan
                                        </span>

                                    @elseif ($target->period_end)

                                        <span class="text-gray-400">
                                            Tidak ditentukan -
                                        </span>

                                        {{ $target->period_end->format('d M Y') }}

                                    @else

                                        <span class="text-gray-400">
                                            Tidak ditentukan
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- STATUS --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 align-top">

                                    @if ($target->status === 'active')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                            Active
                                        </span>

                                    @elseif ($target->status === 'pending')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                            Pending
                                        </span>

                                    @elseif ($target->status === 'completed')

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                                            Completed
                                        </span>

                                    @else

                                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-600">
                                            Inactive
                                        </span>

                                    @endif

                                </td>


                                {{-- ================================================= --}}
                                {{-- ACTIONS --}}
                                {{-- ================================================= --}}

                                <td class="px-6 py-5 align-top">

                                    <div class="flex items-center justify-end gap-2 flex-wrap">


                                        {{-- LIHAT --}}

                                        <a
                                            href="{{ route('kepala_departemen.targets.show', $target) }}"
                                            class="px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-gray-100 transition"
                                        >
                                            Lihat
                                        </a>


                                        {{-- SUB TARGET --}}

                                        <a
                                            href="{{ route('kepala_departemen.targets.sub-targets.index', $target) }}"
                                            class="px-3 py-2 rounded-lg text-sm text-emerald-600 hover:bg-emerald-50 transition"
                                        >
                                            Sub Target
                                        </a>


                                        {{-- EDIT --}}

                                        <a
                                            href="{{ route('kepala_departemen.targets.edit', $target) }}"
                                            class="px-3 py-2 rounded-lg text-sm text-blue-600 hover:bg-blue-50 transition"
                                        >
                                            Edit
                                        </a>


                                        {{-- DELETE --}}

                                        <button
                                            type="button"
                                            onclick="openDeleteModal(
                                                '{{ $target->id }}',
                                                @js($target->title)
                                            )"
                                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition"
                                        >
                                            Hapus
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="{{ auth()->user()->role === 'kepala_departemen' ? 7 : 6 }}"
                                    class="px-6 py-12 text-center"
                                >

                                    <div class="text-gray-400 text-sm">

                                        Belum ada Target departemen.

                                    </div>


                                    <a
                                        href="{{ route('kepala_departemen.targets.create') }}"
                                        class="inline-block mt-3 text-sm font-medium text-emerald-600 hover:text-emerald-700"
                                    >
                                        Tambahkan Target pertama
                                    </a>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- ================================================= --}}
            {{-- PAGINATION --}}
            {{-- ================================================= --}}

            @if ($targets->hasPages())

                <div class="px-6 py-4 border-t border-gray-200">

                    {{ $targets->links() }}

                </div>

            @endif

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- DELETE CONFIRMATION MODAL --}}
{{-- ============================================================= --}}

<div
    id="deleteModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
>

    <div
        class="w-full max-w-md bg-white rounded-2xl shadow-xl overflow-hidden"
    >

        {{-- HEADER --}}

        <div class="px-6 py-5 border-b border-gray-200">

            <div class="flex items-center gap-3">

                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100">

                    <svg
                        class="w-5 h-5 text-red-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >

                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v4m0 4h.01M10.29 3.86l-7.82 13a2 2 0 001.71 3h15.64a2 2 0 001.71-3l-7.82-13a2 2 0 00-3.42 0z"
                        />

                    </svg>

                </div>


                <div>

                    <h3 class="text-lg font-semibold text-gray-800">
                        Hapus Target?
                    </h3>

                    <p class="text-sm text-gray-500">
                        Konfirmasi penghapusan data
                    </p>

                </div>

            </div>

        </div>


        {{-- CONTENT --}}

        <div class="px-6 py-5">

            <p class="text-sm text-gray-600">
                Apakah Anda yakin ingin menghapus target:
            </p>

            <p
                id="deleteTargetName"
                class="mt-2 font-semibold text-gray-800"
            >
            </p>

            <p class="mt-3 text-sm text-red-500">
                Data Target yang telah dihapus tidak dapat dikembalikan.
            </p>

        </div>


        {{-- ACTION --}}

        <div class="px-6 py-4 bg-gray-50 flex justify-end gap-3">

            <button
                type="button"
                onclick="closeDeleteModal()"
                class="px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition"
            >
                Batal
            </button>


            <form
                id="deleteTargetForm"
                method="POST"
            >

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="px-4 py-2.5 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition"
                >
                    Hapus Target
                </button>

            </form>

        </div>

    </div>

</div>


{{-- ============================================================= --}}
{{-- DELETE MODAL JAVASCRIPT --}}
{{-- ============================================================= --}}

<script>

    function openDeleteModal(targetId, targetName) {

        const modal =
            document.getElementById('deleteModal');

        const targetNameElement =
            document.getElementById('deleteTargetName');

        const deleteForm =
            document.getElementById('deleteTargetForm');


        targetNameElement.textContent =
            targetName;


        deleteForm.action =
            "{{ url('/kepala_departemen/targets') }}/" + targetId;


        modal.classList.remove('hidden');

        modal.classList.add('flex');

        document.body.classList.add('overflow-hidden');

    }


    function closeDeleteModal() {

        const modal =
            document.getElementById('deleteModal');


        modal.classList.remove('flex');

        modal.classList.add('hidden');

        document.body.classList.remove('overflow-hidden');

    }


    document
        .getElementById('deleteModal')
        .addEventListener('click', function (event) {

            if (event.target === this) {

                closeDeleteModal();

            }

        });

</script>

</x-app-layout>
