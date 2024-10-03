import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import { VitePWA } from 'vite-plugin-pwa';


export default defineConfig({
    plugins: [
        VitePWA({
              registerType: 'autoUpdate'
          }),
        laravel({
            input: [
                "resources/css/dashboard.css",
                "resources/css/index.css",
                "resources/css/insert.css",
                "resources/css/main-menu.css",
                "resources/css/login.css",
                "resources/js/app.js",
                "resources/js/html5-qrcode.min.js",
                "resources/js/html2canvas.min.js",
                "resources/js/charts.js",
            ],
            refresh: true,
        }),
    ],
});
