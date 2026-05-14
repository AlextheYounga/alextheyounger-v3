const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue'
    ],
    darkMode: true, // or 'media' or 'class'
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans]
            },
            colors: {
                gold: '#F2D024',
                'dark-mode': '#202122',
                'old-gray': '#B8C2CC',
                'dark-mode': '#212121',
                'dark-mode-light': '#34363a',
                'dark-mode-lighter': '#303030',
                'dark-mode-lightest': '#424242',
                'dark-mode-blue': '#3d4852',
                'off-white': '#e6e6e6',
                'off-gray': '#737373',
                'dark-opacity': 'rgba(19,19,19,0.30);',
                'gray-darkest-alpha': 'rgba(56,56,56,.25);',
                burgandy: '#800020'
            }
        }
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography')
    ]
};
