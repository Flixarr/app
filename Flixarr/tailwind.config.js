/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");
const colors = require("tailwindcss/colors");

module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/css/**/*.css",
        "./vendor/usernotnull/tall-toasts/config/**/*.php",
        "./vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php",
    ],
    theme: {
        extend: {
            colors: {
                primary: {
                    dark: colors.blue[700],
                    DEFAULT: colors.blue[600],
                    light: colors.blue[500],
                    muted: colors.blue[300],
                    lighter: colors.blue[100],
                    lightest: colors.blue[50],
                },
                muted: {
                    DEFAULT: colors.gray[500],
                    light: colors.gray[300],
                    lighter: colors.gray[200],
                    lightest: colors.gray[100],
                },
                gray: colors.slate,
            },
        },
    },
    variants: {
        extend: {
            lineClamp: ["focus"],
        },
    },
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
    ],
};
