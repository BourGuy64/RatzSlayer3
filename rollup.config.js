import babel from "rollup-plugin-babel";
import { terser } from "rollup-plugin-terser";

export default({
    input: "src/js/main.js",
    output: {
        file: "src/builds/bundle.js",
        format: "umd"
    },
    plugins: [
        terser(),
        babel({
            exclude: "node_modules/**"
        })
    ]
});
