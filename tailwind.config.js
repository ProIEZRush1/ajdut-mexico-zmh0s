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
                // Paleta AJDUT México — jewel tones cálidos y ELEGANTES (institución de donaciones):
                // teal profundo (confianza), oro cálido (emerald), terracota de acento (coral),
                // neutros cálidos (slate). Digna y con calidez, sin caer en tonos neón/infantiles.
                teal: {
                    50: '#f2f7f6',
                    100: '#e0edeb',
                    200: '#c2dbd8',
                    300: '#95c0bb',
                    400: '#5f9e98',
                    500: '#3d827b',
                    600: '#2e6862',
                    700: '#285450',
                    800: '#244543',
                    900: '#1f3a38',
                    950: '#102523',
                },
                emerald: {
                    50: '#faf6ee',
                    100: '#f3e9d2',
                    200: '#e8d3a4',
                    300: '#dbb96f',
                    400: '#cfa049',
                    500: '#bd8636',
                    600: '#a06d2c',
                    700: '#7f5426',
                    800: '#684424',
                    900: '#583a22',
                },
                coral: {
                    50: '#faf1ec',
                    100: '#f4ded2',
                    200: '#e8bda6',
                    300: '#d99674',
                    400: '#cb724c',
                    500: '#bd5836',
                    600: '#a8462c',
                    700: '#8b3826',
                    800: '#722f24',
                    900: '#5f2921',
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
