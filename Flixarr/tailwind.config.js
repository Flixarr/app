/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    darkMode: "class",
    content: [
        "./resources/views/**/*.blade.php",
        "./app/View/Components/**/*.php",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    DEFAULT: colors.orange[600],
                    light: colors.orange[500],
                    dark: colors.orange[700],
                },
                muted: {
                    DEFAULT: colors.slate[500],
                    light: colors.slate[400],
                    lighter: colors.slate[200],
                    dark: colors.slate[600],
                },
                gray: colors.gray,
                // gray: colors.slate,
                "border-color": "border-gray-700/40",
            },
            screens: {
                phone: "480px", // => @media (min-width: 480px) { ... }
                tablet: "640px", // => @media (min-width: 640px) { ... }
                "tablet-lg": "768px", // => @media (min-width: 640px) { ... }
                laptop: "1024px", // => @media (min-width: 1024px) { ... }
                desktop: "1280px", // => @media (min-width: 1280px) { ... }
            },
            fontFamily: {
                inter: ["Inter", "sans-serif"],
                logo: ["Logo"],
            },
            fontWeight: {
                thin: "100",
                extralight: "200",
                light: "300",
                normal: "400",
                medium: "500",
                semibold: "600",
                bold: "700",
                extrabold: "800",
                black: "900",
            },
            backgroundSize: {
                "size-200": "200% 200%",
            },
            backgroundPosition: {
                "pos-0": "0% 0%",
                "pos-100": "100% 100%",
            },
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
