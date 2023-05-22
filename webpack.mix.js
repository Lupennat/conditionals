let mix = require('laravel-mix')

require('./nova.mix')

mix
  .setPublicPath('dist')
  .js('resources/js/conditionals.js', 'js')
  .vue({ version: 3 })
  .nova('lupennat/conditionals')
  .version();
