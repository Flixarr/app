import { defineConfig, loadEnv } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig(({ mode }) => {
    process.env = { ...process.env, ...loadEnv(mode, process.cwd()) };

    return {
        server: {
            hmr: {
                host: process.env.VITE_DEV_IP,
            },
        },
        plugins: [
            laravel({
                input: ["resources/css/app.css", "resources/js/app.js"],
                refresh: ["app/Http/**", "resources/views/**", "routes/**"],
                usePolling: true,
            }),
        ],
    };
});
