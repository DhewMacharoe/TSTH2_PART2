<nav
    x-data="{ open: false }"
    class="bg-white border-b border-gray-200 shadow-sm"
>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center h-16">

            {{-- =============================== --}}
            {{-- MENU NAVIGASI DESKTOP --}}
            {{-- =============================== --}}

            <div class="flex items-center">

                <div class="hidden sm:flex items-center gap-8">

                    {{-- =============================== --}}
                    {{-- DASHBOARD --}}
                    {{-- =============================== --}}
                
                    @if(!in_array(Auth::user()->role, ['kepala_departemen', 'pimpinan'], true))

                    <a
                        href="{{ route('dashboard') }}"
                        class="
                            px-1 py-5
                            text-sm font-medium
                            transition duration-200
                            border-b-2
                            {{ request()->routeIs('dashboard')
                                ? 'text-blue-600 border-blue-600'
                                : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                            }}
                        "
                    >
                        Dashboard
                    </a>

                @endif
                    {{-- =============================== --}}
                    {{-- MENU ADMIN --}}
                    {{-- =============================== --}}

                    @if(in_array(Auth::user()->role, ['admin', 'web_admin'], true))

                        <a
                            href="{{ route('admin.users.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('admin.users.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Manajemen User
                        </a>


                        <a
                            href="{{ route('admin.departments.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                request()->routeIs('admin.departments.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Manajemen Department
                        </a>

                    @endif


                    {{-- =============================== --}}
                    {{-- MENU KARYAWAN --}}
                    {{-- =============================== --}}

                    @if(in_array(Auth::user()->role, ['employee', 'karyawan'], true))

                        <a
                            href="{{ route('employee.tasks.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('employee.tasks.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Tugas Saya
                        </a>

                        <a
                            href="{{ route('employee.logbooks.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('employee.logbooks.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Logbook Saya
                        </a>

                    @endif

                    {{-- =============================== --}}
                    {{-- MENU PIC GUDANG / PIC LAB --}}
                    {{-- =============================== --}}

                    @if(in_array(Auth::user()->role, ['pic_gudang', 'pic_lab'], true))
                        @php
                            $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
                        @endphp
                        <a
                            href="{{ $urlGudang }}/dasbor"
                            class="
                                px-1 py-5
                                text-sm font-semibold
                                text-amber-700 hover:text-amber-900
                                border-b-2 border-transparent hover:border-amber-400
                                flex items-center gap-1.5 transition
                            "
                        >
                            <span>Sistem Gudang & Lab</span>
                            <span class="text-xs">&rarr;</span>
                        </a>
                    @endif


                    {{-- =============================== --}}
                    {{-- MENU KEPALA DIVISI --}}
                    {{-- =============================== --}}

                    @if(Auth::user()->role === 'kepala_divisi')

                        <a
                            href="{{ route('kepala_divisi.tasks.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('kepala_divisi.tasks.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Pekerjaan
                        </a>


                        <a
                            href="{{ route('kepala_divisi.logbooks.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('kepala_divisi.logbooks.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Review Logbook
                        </a>

                    @endif


                    {{-- =============================== --}}
                    {{-- MENU KEPALA DEPARTEMEN --}}
                    {{-- =============================== --}}

                    @if(Auth::user()->role === 'kepala_departemen')

                        <a
                            href="{{ route('dashboard') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('dashboard')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Dashboard
                        </a>

                        <a
                            href="{{ route('kepala_departemen.projects.index') }}"
                            class="
                                px-1 py-5
                                text-sm font-medium
                                transition duration-200
                                border-b-2
                                {{ request()->routeIs('kepala_departemen.projects.*')
                                    ? 'text-blue-600 border-blue-600'
                                    : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300'
                                }}
                            "
                        >
                            Project
                        </a>

                    @endif


                    {{-- =============================== --}}
                    {{-- MENU PIMPINAN --}}
                    {{-- =============================== --}}

                    @if(Auth::user()->role === 'pimpinan')

                        @foreach (['dashboard' => 'Dashboard', 'monitoring' => 'Monitoring'] as $section => $label)
                            <a
                                href="{{ route('pimpinan.dashboard', array_merge(request()->query(), ['section' => $section])) }}"
                                class="px-1 py-5 text-sm font-medium transition duration-200 border-b-2 {{ request('section', 'dashboard') === $section ? 'text-blue-600 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600 hover:border-blue-300' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach

                    @endif

                </div>

            </div>


            {{-- =============================== --}}
            {{-- USER DROPDOWN --}}
            {{-- =============================== --}}

            {{-- Ekosistem TSTH2 Hub --}}
            <div class="hidden sm:flex items-center gap-2 me-4">
                <a
                    href="{{ config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://tsth2.id')) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200 rounded-lg transition"
                    title="Buka Portal Pengunjung"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                    <span>Portal Publik</span>
                </a>
                <a
                    href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://warehouse.tsth2.id')) }}"
                    target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-emerald-50 hover:text-emerald-700 border border-gray-200 rounded-lg transition"
                    title="Buka Sistem Gudang & Inventaris"
                >
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <span>Sistem Gudang</span>
                </a>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-2">

                <x-dropdown
                    align="right"
                    width="48"
                >

                    <x-slot name="trigger">

                        <button
                            class="
                                inline-flex items-center gap-2
                                px-3 py-2
                                text-sm font-medium
                                text-gray-600
                                bg-white
                                border border-transparent
                                rounded-md
                                hover:text-blue-600
                                hover:bg-gray-50
                                focus:outline-none
                                transition duration-200
                            "
                        >
                            {{-- Profile Photo --}}
                            @if (Auth::user()->profile_photo)
                                <img
                                    src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                    alt="Foto Profil"
                                    class="w-7 h-7 rounded-full object-cover border border-gray-200"
                                >
                            @else
                                <span class="w-7 h-7 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-xs border border-emerald-200">
                                    {{ Auth::user()->display_initial }}
                                </span>
                            @endif

                            <span>{{ Auth::user()->display_name }}</span>

                            <svg
                                class="fill-current h-4 w-4"
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"
                                />
                            </svg>

                        </button>

                    </x-slot>


                    {{-- DROPDOWN CONTENT --}}
                    <x-slot name="content">

                        <div class="px-4 py-3 border-b border-gray-100 flex items-center gap-3">

                            {{-- Mini Profile Photo --}}
                            @if (Auth::user()->profile_photo)
                                <img
                                    src="{{ Storage::url(Auth::user()->profile_photo) }}"
                                    alt="Foto Profil"
                                    class="w-9 h-9 rounded-full object-cover border border-gray-200 shrink-0"
                                >
                            @else
                                <span class="w-9 h-9 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm border border-emerald-200 shrink-0">
                                    {{ Auth::user()->display_initial }}
                                </span>
                            @endif

                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-800 truncate">
                                    {{ Auth::user()->display_name }}
                                </p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ Auth::user()->email }}
                                </p>
                            </div>

                        </div>

                        {{-- Lihat Profil --}}
                        <x-dropdown-link :href="route('profile.show')">
                            Lihat Profil
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        {{-- Ekosistem TSTH2 Hub --}}
                        <div class="px-4 py-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Ekosistem TSTH2 Hub
                        </div>
                        <x-dropdown-link :href="config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://tsth2.id'))" target="_blank">
                            🌐 Portal Publik
                        </x-dropdown-link>
                        <x-dropdown-link :href="config('app.url_gudang', env('URL_GUDANG', 'http://warehouse.tsth2.id'))" target="_blank">
                            📦 Sistem Gudang
                        </x-dropdown-link>

                        <div class="border-t border-gray-100"></div>

                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                        >

                            @csrf

                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="
                                    event.preventDefault();
                                    this.closest('form').submit();
                                "
                            >
                                Log Out
                            </x-dropdown-link>

                        </form>

                    </x-slot>

                </x-dropdown>

            </div>


            {{-- =============================== --}}
            {{-- HAMBURGER MOBILE --}}
            {{-- =============================== --}}

            <div class="-me-2 flex items-center sm:hidden">

                <button
                    @click="open = ! open"
                    class="
                        inline-flex
                        items-center
                        justify-center
                        p-2
                        rounded-md
                        text-gray-500
                        hover:text-blue-600
                        hover:bg-gray-100
                        focus:outline-none
                        transition
                    "
                >

                    <svg
                        class="h-6 w-6"
                        stroke="currentColor"
                        fill="none"
                        viewBox="0 0 24 24"
                    >

                        <path
                            :class="{
                                'hidden': open,
                                'inline-flex': ! open
                            }"
                            class="inline-flex"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />

                        <path
                            :class="{
                                'hidden': ! open,
                                'inline-flex': open
                            }"
                            class="hidden"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />

                    </svg>

                </button>

            </div>

        </div>

    </div>


    {{-- =============================== --}}
    {{-- MOBILE MENU --}}
    {{-- =============================== --}}

    <div
        :class="{
            'block': open,
            'hidden': ! open
        }"
        class="hidden sm:hidden bg-white border-t border-gray-200"
    >

        <div class="pt-2 pb-3 space-y-1">

            {{-- DASHBOARD --}}
            
        @if(!in_array(Auth::user()->role, ['kepala_departemen', 'pimpinan'], true))

            <a
                href="{{ route('dashboard') }}"
                class="
                    block px-4 py-3
                    text-sm font-medium
                    border-l-4
                    transition
                    {{ request()->routeIs('dashboard')
                        ? 'text-blue-600 bg-blue-50 border-blue-600'
                        : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                    }}
                "
            >
                Dashboard
            </a>

        @endif
            {{-- ADMIN --}}
            @if(in_array(Auth::user()->role, ['admin', 'web_admin'], true))

                <a
                    href="{{ route('admin.users.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('admin.users.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Manajemen User
                </a>


                <a
                <a
                    href="{{ route('admin.departments.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('admin.departments.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Manajemen Department & Division
                </a>

            @endif


            {{-- EMPLOYEE --}}
            @if(in_array(Auth::user()->role, ['employee', 'karyawan'], true))

                <a
                    href="{{ route('employee.tasks.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('employee.tasks.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Pekerjaan Saya
                </a>


                <a
                    href="{{ route('employee.logbooks.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('employee.logbooks.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Logbook Saya
                </a>

            @endif

            {{-- PIC GUDANG & PIC LAB --}}
            @if(in_array(Auth::user()->role, ['pic_gudang', 'pic_lab'], true))
                @php
                    $urlGudang = rtrim(config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')), '/');
                @endphp
                <a
                    href="{{ $urlGudang }}/dasbor"
                    class="block px-4 py-3 text-sm font-semibold text-amber-700 hover:bg-amber-50 border-l-4 border-amber-500 transition"
                    target="_blank"
                >
                    Sistem Warehouse & Gudang &rarr;
                </a>
            @endif


            {{-- KEPALA DIVISI --}}
            @if(Auth::user()->role === 'kepala_divisi')

                <a
                    href="{{ route('kepala_divisi.tasks.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('kepala_divisi.tasks.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Pekerjaan
                </a>


                <a
                    href="{{ route('kepala_divisi.logbooks.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('kepala_divisi.logbooks.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Review Logbook
                </a>

            @endif


            {{-- KEPALA DEPARTEMEN --}}
            @if(Auth::user()->role === 'kepala_departemen')

                <a
                    href="{{ route('dashboard') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('dashboard')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Monitoring
                </a>

                                <a
                    href="{{ route('kepala_departemen.projects.index') }}"
                    class="
                        block px-4 py-3
                        text-sm font-medium
                        border-l-4
                        transition
                        {{ request()->routeIs('kepala_departemen.projects.*')
                            ? 'text-blue-600 bg-blue-50 border-blue-600'
                            : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50'
                        }}
                    "
                >
                    Project
                </a>


            @endif


            {{-- PIMPINAN --}}
            @if(Auth::user()->role === 'pimpinan')

                @foreach (['dashboard' => 'Dashboard', 'monitoring' => 'Monitoring'] as $section => $label)
                    <a
                        href="{{ route('pimpinan.dashboard', array_merge(request()->query(), ['section' => $section])) }}"
                        class="block px-4 py-3 text-sm font-medium border-l-4 transition {{ request('section', 'dashboard') === $section ? 'text-blue-600 bg-blue-50 border-blue-600' : 'text-gray-600 border-transparent hover:text-blue-600 hover:bg-gray-50' }}"
                    >
                        {{ $label }}
                    </a>
                @endforeach

            @endif

        </div>


        {{-- =============================== --}}
        {{-- USER MOBILE --}}
        {{-- =============================== --}}

        <div class="pt-4 pb-1 border-t border-gray-200">

            <div class="px-4 flex items-center gap-3">

                @if (Auth::user()->profile_photo)
                    <img
                        src="{{ Storage::url(Auth::user()->profile_photo) }}"
                        alt="Foto Profil"
                        class="w-10 h-10 rounded-full object-cover border border-gray-200 shrink-0"
                    >
                @else
                    <span class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-semibold text-sm border border-emerald-200 shrink-0">
                        {{ Auth::user()->display_initial }}
                    </span>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">
                        {{ Auth::user()->display_name }}
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {{ Auth::user()->email }}
                    </div>
                </div>

            </div>


            {{-- MOBILE MENU ITEMS --}}
            <div class="mt-3 space-y-1">

                <a
                    href="{{ route('profile.edit') }}"
                    class="block px-4 py-3 text-sm font-medium text-gray-600 hover:text-blue-600 hover:bg-gray-50 transition"
                >
                    Edit Profil
                </a>

                <div class="border-t border-gray-100 my-1"></div>
                <div class="px-4 py-1 text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                    Lompat Web TSTH2
                </div>
                <a
                    href="{{ config('app.url_pengunjung', env('URL_PENGUNJUNG', 'http://localhost:8000')) }}"
                    target="_blank"
                    class="block px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 transition"
                >
                    🌐 Portal Pengunjung (:8000)
                </a>
                <a
                    href="{{ config('app.url_gudang', env('URL_GUDANG', 'http://localhost:8002')) }}"
                    target="_blank"
                    class="block px-4 py-2 text-sm font-medium text-emerald-700 hover:bg-emerald-50 transition"
                >
                    📦 Sistem Gudang (:8002)
                </a>
                <div class="border-t border-gray-100 my-1"></div>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >

                    @csrf

                    <button
                        type="submit"
                        class="
                            w-full
                            text-left
                            px-4 py-3
                            text-sm font-medium
                            text-gray-600
                            hover:text-red-600
                            hover:bg-red-50
                            transition
                        "
                    >
                        Log Out
                    </button>

                </form>

            </div>

        </div>

    </div>

</nav>