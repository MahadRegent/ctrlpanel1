import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import path from "path";

export default defineConfig({
    build: {
        manifest: "manifest.json",
    },
    publicDir: 'public',
    root: ".",
    plugins: [
        laravel({
            publicDirectory: "../../public",
            input: [
                "css/app.css",

                "js/app.js",
                "js/coloris.js",
                "js/focus-trap.js",
                "js/tinymce.js",
                "js/pace.js",
                "js/iconify-icon.js",
            ],
            buildDirectory: "themes/phoenix",
        }),
        {
            name: "blade",
            handleHotUpdate({ file, server }) {
                if (file.endsWith(".blade.php")) {
                    server.ws.send({
                        type: "full-reload",
                        path: "*",
                    });
                }
            },
        }
    ],
    resolve: {
        alias: {
            '@': '/themes/phoenix/js',
            '~bootstrap': path.resolve('node_modules/bootstrap'),
        }
    },
});
