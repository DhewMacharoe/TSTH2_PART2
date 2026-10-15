import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // tambahkan breakpoint ekstra untuk smartwatch & mobile kecil
    screens: {
        xs: '320px', // smartwatch & small mobile
        ...defaultTheme.screens,
    },
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                heading: ['Outfit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#054439',
                    950: '#02231d',
                    DEFAULT: '#054439',
                    light: '#0a6655',
                    dark: '#02231d',
                },
                accent: {
                    DEFAULT: '#c09e5c',
                    light: '#e6c584',
                    dark: '#9a7e4a',
                },
            },
        },
    },

    plugins: [forms],
};