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
                // Paleta AJDUT México — cálida, VIVA y con acento, sin perder elegancia:
                // azul vibrante (teal), dorado ámbar (emerald), coral cálido de acento (coral)
                // y neutros cálidos (slate). Tonos saturados y luminosos para una página con energía.
                teal: {
                    50: '#eef6ff',
                    100: '#d9eaff',
                    200: '#bcdcff',
                    300: '#8ec6ff',
                    400: '#59a6ff',
                    500: '#2f84f7',
                    600: '#1c66e6',
                    700: '#1a50c4',
                    800: '#1c439e',
                    900: '#1c3b7d',
                    950: '#13244d',
                },
                emerald: {
                    50: '#fff9ec',
                    100: '#fef0c8',
                    200: '#fde08d',
                    300: '#fccb52',
                    400: '#fbb327',
                    500: '#f5960d',
                    600: '#d97406',
                    700: '#b45309',
                    800: '#92400e',
                    900: '#78350f',
                },
                coral: {
                    50: '#fff1ec',
                    100: '#ffe0d5',
                    200: '#ffc0aa',
                    300: '#ff9973',
                    400: '#fb6f45',
                    500: '#f24f26',
                    600: '#dc3d18',
                    700: '#b62f14',
                    800: '#8f2613',
                    900: '#6e1f11',
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
