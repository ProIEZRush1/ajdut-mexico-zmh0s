import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                serif: ['"Playfair Display"', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Paleta elegante AJDUT México: azul profundo (reemplaza teal),
                // dorado suave (reemplaza emerald) y neutros cálidos (reemplaza slate).
                teal: {
                    50: '#f5f7fb',
                    100: '#e8edf6',
                    200: '#cbd8ea',
                    300: '#a3b8d6',
                    400: '#7292bd',
                    500: '#4d70a0',
                    600: '#385581',
                    700: '#2c4368',
                    800: '#21324e',
                    900: '#182438',
                    950: '#0f1725',
                },
                emerald: {
                    50: '#fdf9ee',
                    100: '#faf0d2',
                    200: '#f3dea3',
                    300: '#e9c574',
                    400: '#dcaa4e',
                    500: '#c68f34',
                    600: '#96681f',
                    700: '#78521b',
                    800: '#603f15',
                    900: '#4d3311',
                },
                slate: {
                    50: '#fafaf9',
                    100: '#f5f5f4',
                    200: '#e7e5e4',
                    300: '#d6d3d1',
                    400: '#a8a29e',
                    500: '#78716c',
                    600: '#57534e',
                    700: '#44403c',
                    800: '#292524',
                    900: '#1c1917',
                    950: '#0c0a09',
                },
            },
        },
    },

    plugins: [forms],
};
