import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './app/Http/Controllers/SettingsController.php',
    ],

    safelist: [
        'bg-sky-100',
        'text-sky-600',
        'bg-emerald-100',
        'text-emerald-600',
        'bg-violet-100',
        'text-violet-600',
        'bg-orange-100',
        'text-orange-600',
        'bg-sky-500',
        'bg-emerald-500',
        'bg-violet-500',
        'bg-orange-500',
        'bg-slate-500',
        'bg-rose-500',
        'bg-cyan-500',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
