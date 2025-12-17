import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                primary: "#D0BB95",
                "primary-dark": "#bfa373",
                "background-light": "#f7f7f6",
                "background-dark": "#1d1a15",
                "text-light": "#171511",
                "text-dark": "#ecebe9",
                "surface-light": "#f0eeea",
                "surface-dark": "#2a2721"
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ["Plus Jakarta Sans"],
            },
        },
    },

    plugins: [forms],
};
