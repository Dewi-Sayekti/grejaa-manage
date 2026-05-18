import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'cream': {
                    50: '#fffef9',
                    100: '#fffdf3',
                    200: '#fff8e6',
                    300: '#fff5d9',
                    400: '#fff1bf',
                    500: '#ffeba3',
                    600: '#ffe587',
                },
                'gold': {
                    50: '#fffbf0',
                    400: '#d4af37',
                    500: '#b8860b',
                    600: '#8b6914',
                },
                'text': {
                    'dark': '#2c3e50',
                    'light': '#555555',
                },
            },
            fontFamily: {
                sans: ['Figtree', 'Inter', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            spacing: {
                '128': '32rem',
                '144': '36rem',
            },
            maxWidth: {
                '7xl': '80rem',
                '8xl': '88rem',
            },
            boxShadow: {
                'soft': '0 2px 8px rgba(0, 0, 0, 0.08)',
                'medium': '0 4px 12px rgba(0, 0, 0, 0.12)',
            },
        },
    },

    plugins: [forms],
};
