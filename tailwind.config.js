/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',          // All Blade templates
        './resources/js/**/*.js',                    // All JS files
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: [
                    'Instrument Sans',
                    'ui-sans-serif',
                    'system-ui',
                    'sans-serif',
                    'Apple Color Emoji',
                    'Segoe UI Emoji',
                    'Segoe UI Symbol',
                    'Noto Color Emoji'
                ],
            },
            colors: {
                primary: '#1F2937',   // Dark gray
                secondary: '#3B82F6', // Blue
            },
        },
    },
    plugins: [],
};
