module.exports = {
    env: {
        browser: true,
        es2021: true,
        commonjs: true,
        es6: true,
    },
    extends: [
        "eslint:recommended",
        "plugin:vue/vue3-essential",
        "plugin:prettier/recommended",
        "prettier/vue",
    ],
    overrides: [],
    parserOptions: {
        ecmaVersion: "latest",
        sourceType: "module",
    },
    plugins: ["vue", "prettier"],
    rules: {
        "vue/no-v-html": "off",
        "vue/prop-name-casing": "off",
        "no-console": "off",
        "no-unused-vars": "off",
        camelcase: "off",

        "prettier/prettier": [
            "error",
            {
                printWidth: 120,
                tabWidth: 2,
                useTabs: false,
                singleQuote: true,
                trailingComma: "all",
                arrowParens: "avoid",
                semi: false,
                proseWrap: "preserve",
            },
        ],
    },
};
