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
            fontFamily: {
                sans: ['Vazirmatn', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                yadak: {
                    blue: {
                        950: '#00244F',
                        900: '#00326D',
                        800: '#0A3C78',
                    },
                    red: {
                        600: '#E51B22',
                        500: '#F01E28',
                    },
                },
            },
            boxShadow: {
                'yadak-soft': '0 18px 50px rgba(15, 23, 42, 0.08)',
                'yadak-blue': '0 24px 70px rgba(0, 50, 109, 0.18)',
                'yadak-red': '0 18px 40px rgba(229, 27, 34, 0.22)',
            },
            borderRadius: {
                '4xl': '2rem',
            },
        },
    },

    plugins: [forms],
};
