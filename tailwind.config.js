/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {
            fontFamily: {
                montserrat: ['"Montserrat"', "system-ui"],
            },
            colors: {
                'fondo': '#f4f6f9'
            }
        },
    },
    plugins: [
        require("flowbite/plugin"),
        
    ],
};
//<script src="../path/to/flowbite/dist/flowbite.min.js"></script>
