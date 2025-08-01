import "@melloware/coloris/dist/coloris.css";
import { coloris, init } from "@melloware/coloris";

init();
coloris({
    el: ".coloris",
    themeMode: "dark",
    alpha: false,
    inline: false,
    swatches: [
        "#067bc2",
        "#84bcda",
        "#80e377",
        "#ecc30b",
        "#f37748",
        "#d56062",
    ],
});
