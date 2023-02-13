// npm install -g eslint eslint-plugin-vue
// eslint . --resolve-plugins-relative-to="C:\Users\%USERNAME%\AppData\Roaming\npm\node_modules" --ext .js,.vue,.blade.php --fix
// https://eslint.org/docs/rules/

module.exports = {
    env: {
        browser: true,
        es6: true,
        jquery: true,
        node: true,
    },
    extends: [
        'eslint:recommended',
        'plugin:vue/recommended',
    ],
    parserOptions: {
        sourceType: 'module',
    },
    globals: {
        Vue: false,
        _: false,
    },
    rules: {
        'array-bracket-spacing': [
            'error',
            'never',
        ],
        'brace-style': [
            'error',
            '1tbs',
            { allowSingleLine: true },
        ],
        camelcase: [
            'warn',
        ],
        'comma-dangle': [
            'error',
            'always-multiline',
        ],
        'comma-spacing': [
            'error',
        ],
        'computed-property-spacing': [
            'error',
        ],
        eqeqeq: [
            'error',
        ],
        'func-name-matching': [
            'error',
        ],
        'function-paren-newline': [
            'error',
            'consistent',
        ],
        indent: [
            'error',
            4,
        ],
        'linebreak-style': [
            'error',
            'unix',
        ],
        'no-extra-parens': [
            'error',
            'all',
        ],
        'no-tabs': [
            'error',
        ],
        'no-trailing-spaces': [
            'error',
        ],
        'quote-props': [
            'error',
            'as-needed',
        ],
        quotes: [
            'error',
            'single',
        ],
        semi: [
            'error',
            'always',
            { omitLastInOneLineBlock: true },
        ],
        'space-before-blocks': [
            'error',
            'always',
        ],
        'space-before-function-paren': [
            'error', {
                anonymous: 'always',
                named: 'never',
                asyncArrow: 'always',
            },
        ],
        // VueJS
        'vue/attributes-order': [
            'error',
            {
                order: [
                    'DEFINITION',
                    'CONDITIONALS',
                    'LIST_RENDERING',
                    'EVENTS',
                    'RENDER_MODIFIERS',
                    'UNIQUE',
                    'GLOBAL',
                    'TWO_WAY_BINDING',
                    'OTHER_DIRECTIVES',
                    'OTHER_ATTR',
                    'CONTENT',
                ],
            },
        ],
        'vue/html-closing-bracket-newline': [
            'error', {
                singleline: 'never',
                multiline: 'never',
            },
        ],
        'vue/html-closing-bracket-spacing': [
            'error', {
                startTag: 'never',
                endTag: 'never',
                selfClosingTag: 'never',
            },
        ],
        'vue/html-indent': [
            'error',
            4,
        ],
        'vue/max-attributes-per-line': [
            'error',
            { singleline: 12 },
        ],
        'vue/no-v-html': [
            0,
        ],
        'vue/singleline-html-element-content-newline': [
            0,
        ],
    },
    plugins: ["@html-eslint"],
    overrides: [
    {
        files: ["*.html"],
        parser: "@html-eslint/parser",
        extends: ["plugin:@html-eslint/recommended"],
    }
  ],
};
