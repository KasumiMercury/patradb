const defaultTheme = require("tailwindcss/defaultTheme");
const plugin = require("tailwindcss/plugin");

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
        fontFamily: {
          body: ['Lato', '"Zen Kaku Gothic Antique"','sans-serif'],
        },
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
                Titan: ["Titan One"],
                Abril: ["Abril Fatface"],
                Zen: ["Zen Kaku Gothic Antique"],
                pGothic: ["MS Ｐゴシック"],
                Lato: ["Lato"],
                mPlus: ["M PLUS Rounded 1c"],
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
                float: {
                    "0%, 50%, 100%": {
                        transform: "translate(0,0)",
                    },
                    "25%": {
                        transform: "translate(0,-5px) rotate(-5deg)",
                    },
                    "75%": {
                        transform: "translate(0,5px) rotate(5deg)",
                    },
                }
            },
            animation: {
                "spin-slow": "spin 3s linear infinite",
                "side-bounce": "side_bounce 1s infinite",
                "float": "float 10s ease infinite",
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
                "custom-shadow":"#534d53"
            },
        },
    },
    daisyui: {
      themes : [
        {
          myTheme: {
            'primary': '#D8346E',
            'secondary': '#2d2a2d',
            'accent': '#F8BBD0',
            'neutral': '#ffc9e3',
            'base-100': '#fffafb',
            'info': '#2196F3',
            'success': '#4CAF50',
            'warning': '#FFC107',
            'error': '#F44336',
          },
        },
    ],
  },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("tailwindcss-safe-area"),
        require("daisyui"),
        plugin(function ({ addUtilities, addComponents, e, prefix, config }) {
          const newUtilities = {
            ".horizontal-tb": {
              writingMode: "horizontal-tb",
            },
            ".vertical-rl": {
              writingMode: "vertical-rl",
            },
            ".vertical-lr": {
              writingMode: "vertical-lr",
            },
            ".drag-none": {
              "-webkit-user-drag": "none",
              "-moz-user-drag": "none",
              "user-drag": "none",
            },
            ".title-display":{
              "word-break": "break-word",
            }
          };
          addUtilities(newUtilities);
        }),
    ],
};
