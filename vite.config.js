import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";


export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/css/dashboard.css",
                "resources/css/index.css",
                "resources/css/insert.css",
                "resources/css/main-menu.css",
                "resources/css/login.css",
                "resources/css/relatorios.css",
                "resources/css/relatorio-vereador.css",
                "resources/js/app.js",
            ],
            refresh: true,
        }),
    ],
});
