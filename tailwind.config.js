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
                serif: ['"Fraunces"', ...defaultTheme.fontFamily.serif],
                accent: ['"Caveat"', 'cursive'],
            },
            colors: {
                // Paleta AJDUT México — cálida, viva y con acento, sin perder elegancia:
                // azul profundo (teal), dorado miel (emerald), coral cálido de acento (coral)
                // y neutros cálidos (slate).
                teal: {
                    50: '#f5f8fd',
                    100: '#e8f0fb',
                    200: '#c9ddf5',
                    300: '#9dc0ea',
                    400: '#6b9adb',
                    500: '#4677c4',
                    600: '#345ba6',
                    700: '#284a87',
                    800: '#213c6c',
                    900: '#1c3157',
                    950: '#111d38',
                },
                emerald: {
                    50: '#fdf7ec',
                    100: '#fbedd6',
                    200: '#f6ddb1',
                    300: '#f0cb8c',
                    400: '#e9b563',
                    500: '#dd9a34',
                    600: '#b87927',
                    700: '#8f5d20',
                    800: '#6b451a',
                    900: '#4a2f0f',
                },
                coral: {
                    50: '#fdf1ea',
                    100: '#fbe2d3',
                    200: '#f7c5ab',
                    300: '#f2a37f',
                    400: '#ec8058',
                    500: '#e35f3a',
                    600: '#c74b2f',
                    700: '#a13a26',
                    800: '#7c2c1d',
                    900: '#5c2015',
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
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                    '50%': { transform: 'translateY(-10px) rotate(2deg)' },
                },
                'float-slow': {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-16px)' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
                heartbeat: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '15%': { transform: 'scale(1.18)' },
                    '30%': { transform: 'scale(1)' },
                    '45%': { transform: 'scale(1.12)' },
                    '60%': { transform: 'scale(1)' },
                },
                'fade-in-up': {
                    '0%': { opacity: '0', transform: 'translateY(14px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
            },
            animation: {
                float: 'float 6s ease-in-out infinite',
                'float-slow': 'float-slow 8s ease-in-out infinite',
                wiggle: 'wiggle 1.8s ease-in-out infinite',
                heartbeat: 'heartbeat 2.4s ease-in-out infinite',
                'fade-in-up': 'fade-in-up 0.6s ease-out both',
            },
        },
    },

    plugins: [forms],
};
