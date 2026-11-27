<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            Tambah Departemen
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Data Departemen
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Tambahkan departemen baru ke dalam struktur organisasi.
                    </p>
                </div>

                <form
                    action="{{ route('admin.departments.store') }}"
                    method="POST"
                    autocomplete="off"
                >
                    @csrf

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
                            value="{{ old('name') }}"
                            placeholder="Contoh: Riset dan Inovasi"
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
                            Simpan Departemen
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>

</x-app-layout>