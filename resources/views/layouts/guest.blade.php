<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        Sistem Logbook Karyawan
    </title>


    {{-- FONT --}}
    <link
        rel="preconnect"
        href="https://fonts.bunny.net"
    >

    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap"
        rel="stylesheet"
    />


    {{-- VITE --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>


<body class="font-sans text-gray-900 antialiased">

    <div
        class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-8"
    >


        {{-- CARD UTAMA --}}
        <div
            class="w-full sm:max-w-md bg-white shadow-lg rounded-2xl px-6 py-8"
        >


            {{-- IDENTITAS SISTEM --}}
            <div class="text-center mb-8">

                <a
                    href="/"
                    class="inline-flex flex-col items-center"
                >

                    {{-- ICON --}}
                    <div
                        class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-md"
                    >

                        <span class="text-3xl">
                            📋
                        </span>

                    </div>


                    {{-- JUDUL --}}
                    <h1
                        class="mt-4 text-2xl font-bold text-gray-800"
                    >

                        Sistem Logbook Karyawan

                    </h1>


                    {{-- SUBTITLE --}}
                    <p
                        class="mt-1 text-sm text-gray-500"
                    >

                        Monitoring Aktivitas Karyawan Terintegrasi

                    </p>

                </a>

            </div>


            {{-- GARIS PEMISAH --}}
            <div class="border-t border-gray-200 mb-6"></div>


            {{-- FORM LOGIN / REGISTER --}}
            <div>

                {{ $slot }}

            </div>


            {{-- GARIS PEMISAH --}}
            <div class="border-t border-gray-200 mt-8 mb-5"></div>


            {{-- FOOTER --}}
            <div class="text-center">

                <p class="text-sm text-gray-500">

                    © 2026 Sistem Logbook Karyawan

                </p>

            </div>


        </div>

    </div>

</body>

</html>