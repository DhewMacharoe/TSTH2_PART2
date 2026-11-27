<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Edit Departemen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Edit Data Departemen
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Perbarui nama departemen yang digunakan dalam struktur organisasi.
                    </p>
                </div>


                <form
                    action="{{ route('admin.departments.update', $department) }}"
                    method="POST"
                    autocomplete="off"
                >
                    @csrf
                    @method('PUT')


                    {{-- NAMA DEPARTMENT --}}
                    <div class="mb-6">

                        <label
                            for="name"
                            class="block mb-2 font-medium text-gray-700"
                        >
                            Nama Departemen
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            value="{{ old('name', $department->name) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500"
                            required
                            autofocus
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    {{-- INFORMASI PENGGUNAAN --}}
                    <div class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">

                        <p class="text-sm font-medium text-gray-700 mb-2">
                            Informasi Departemen
                        </p>

                        <div class="grid grid-cols-2 gap-4">

                            <div>
                                <p class="text-xs text-gray-500">
                                    Jumlah Divisi
                                </p>

                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $department->divisions()->count() }}
                                </p>
                            </div>

                            <div>
                                <p class="text-xs text-gray-500">
                                    Jumlah User
                                </p>

                                <p class="text-lg font-semibold text-gray-800">
                                    {{ $department->users()->count() }}
                                </p>
                            </div>

                        </div>

                    </div>


                    {{-- BUTTON --}}
                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('admin.departments.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                        >
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                        >
                            Update Departemen
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>