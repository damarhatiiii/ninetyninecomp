// tailwind.config.js
module.exports = {
content: [
    "./**/*.{html,js,php}",
    "./node_modules/flowbite/**/*.js",
],
theme: {
    extend: {
        fontFamily: {
            'sans': ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'sans-serif'],
        },
    },
},
plugins: [
    require('flowbite/plugin'),
],
};
