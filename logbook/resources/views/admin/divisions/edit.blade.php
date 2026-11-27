<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Divisi
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Edit Divisi
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Perbarui informasi Divisi pada Departemen
                        <span class="font-medium text-gray-700">
                            {{ $department->name }}
                        </span>.
                    </p>
                </div>

                <form
                    action="{{ route('admin.departments.divisions.update', [$department, $division]) }}"
                    method="POST"
                >
                    @csrf
                    @method('PUT')

                    {{-- DEPARTMENT --}}
                    <div class="mb-5">
                        <label
                            for="department"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Departemen
                        </label>

                        <input
                            type="text"
                            id="department"
                            value="{{ $department->name }}"
                            readonly
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-600 cursor-not-allowed"
                        >
                    </div>

                    {{-- NAMA DIVISION --}}
                    <div class="mb-6">
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Nama Divisi
                        </label>

                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $division->name) }}"
                            required
                            autofocus
                            autocomplete="off"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg
                                   focus:ring-2 focus:ring-blue-500
                                   focus:border-blue-500"
                        >

                        @error('name')
                            <p class="mt-2 text-sm text-red-600">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- BUTTON --}}
                    <div class="flex items-center justify-end gap-3">

                        <a
                            href="{{ route('admin.departments.index') }}"
                            class="inline-flex items-center gap-2 px-4 py-2
                                   bg-blue-600 hover:bg-blue-700
                                   text-white rounded-lg font-medium transition"
                        >
                            ← Kembali ke Manajemen Departemen
                        </a>

                        <button
                            type="submit"
                            class="px-4 py-2
                                   bg-blue-600 hover:bg-blue-700
                                   text-white rounded-lg font-medium transition"
                        >
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>