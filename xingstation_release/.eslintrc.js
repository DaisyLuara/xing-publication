// EsLint规则设置
// 使用前 yarn 以安装最新的依赖包
module.exports = {
  root: true, //  eslint找到这个标识后，不会再去父文件夹中找eslint的配置文件
  // parser: 'babel-eslint',   //使用babel-eslint来作为eslint的解析器
  parserOptions: {
    // 设置解析器选项
    parser: 'babel-eslint',
    ecmaVersion: 2017,
    sourceType: 'module',
  },
  // https://github.com/feross/standard/blob/master/RULES.md#javascript-standard-style
  extends: 'standard', // 继承eslint-config-standard里面提供的lint规则
  // required to lint *.vue files
  plugins: [
    // 使用的插件eslint-plugin-html. 写配置文件的时候，可以省略eslint-plugin-
    // 'html',
    'vue'
  ],
  extends: [
    // add more generic rulesets here, such as:
    // 'eslint:recommended',
    'plugin:vue/essential',
    'plugin:vue/recommended',
    'plugin:vue/base',
    'plugin:vue/strongly-recommended',
  ],
  rules: {
    // override/add rules settings here, such as:
    // 'vue/no-unused-vars': 'error'
    // 'generator-star-spacing': 'off',
  },
}
