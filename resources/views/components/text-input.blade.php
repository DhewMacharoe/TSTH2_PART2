@props(['disabled' => false])

<input
    @disabled($disabled)

    {{ $attributes->merge([
        'class' => 'w-full bg-gray-50 border border-gray-300 text-gray-900 rounded-lg shadow-sm placeholder-gray-400 focus:bg-white focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50'
    ]) }}
>