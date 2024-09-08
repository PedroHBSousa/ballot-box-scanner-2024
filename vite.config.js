import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/dashboard.css",
                "resources/js/app.js",
                "resources/css/index.css",
                "resources/css/insert.css",
            ],
            refresh: true,
        }),
    ],
});
