module.exports = {
    env: {
        browser: true,
        es2021: true,
        node: true,
    },
    extends: ["eslint:recommended", "plugin:vue/recommended", "prettier"],
    rules: {
        // override/add rules settings here, such as:
        // 'vue/no-unused-vars': 'error'
    },
};
