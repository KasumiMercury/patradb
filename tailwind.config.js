const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                Titan: ["Titan One"],
            },
            borderWidth: {
                8: "8px",
                12: "12px",
            },
            keyframes: {
                wiggle: {
                    "0%, 100%": { transform: "rotate(-3deg)" },
                    "50%": { transform: "rotate(3deg)" },
                },
                side_bounce: {
                    "0%, 100%": {
                        transform: "translateX(0)",
                        "animation-timing-function": "cubic-bezier(0.8,0,1,1)",
                    },
                    "50%": {
                        transform: "translateX(25%)",
                        "animation-timing-function": "cubic-bezier(0,0,0.2,1)",
                    },
                },
            },
            animation: {
                "spin-slow": "spin 3s linear infinite",
                "side-bounce": "side_bounce 1s infinite",
            },
            backgroundImage: {
                "gradient-radial": "radial-gradient(var(--tw-gradient-stops))",
            },
            colors: {
                "ptr-main": "#D8346E",
                "ptr-dark-pink": "#c20063",
                "ptr-pink":"#ff99cd",
                "ptr-light-pink": "#ffc9e3",
                "ptr-white": "#fffafb",
                "ptr-dark-brown": "#2d2a2d",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("tailwindcss-safe-area"),
    ],
};
