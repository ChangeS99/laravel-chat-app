/** @type {import('tailwindcss').Config} */
module.exports = {
    mode: "jit",
    content: [
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {},
    },
    plugins: [require("@tailwindcss/forms")],
};
