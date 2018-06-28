var Encore = require('@symfony/webpack-encore')

const CopyWebpackPlugin = require('copy-webpack-plugin')
const webpack = require('webpack')
const path = require('path')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

Encore
  .setOutputPath('web/assets/')
  .setPublicPath('/assets')
  .addEntry('js/admin/index', './app/Resources/assets/js/admin/index.js')
  .addEntry('js/main', './app/Resources/assets/js/main.js')
  .addEntry('js/home', './app/Resources/assets/js/home.js')
  .addEntry('js/page', './app/Resources/assets/js/page.js')
  .addEntry('js/calendar', './app/Resources/assets/js/calendar.js')
  .addEntry('js/calendar-api', './app/Resources/assets/js/calendar-api.js')
  .addEntry('js/calendar-registration', './app/Resources/assets/js/calendar-registration.js')
  .addEntry('js/cart', './app/Resources/assets/js/cart.js')
  .addEntry('js/editions', './app/Resources/assets/js/editions.js')
  .addEntry('js/carousel', './app/Resources/assets/js/carousel.js')
  .addStyleEntry('css/main', './app/Resources/assets/sass/main.scss')
  .addStyleEntry('css/calendar', './app/Resources/assets/sass/calendar.scss')
  .addStyleEntry('css/calendar-registration', './app/Resources/assets/sass/calendar-registration.scss')
  .addStyleEntry('css/editions', './app/Resources/assets/sass/editions.scss')
  .addStyleEntry('css/carousel', './app/Resources/assets/sass/carousel.scss')
  .addStyleEntry('css/Draft/Draft', './app/Resources/assets/css/Draft.css')
  .addStyleEntry('css/Draft/Editor', './app/Resources/assets/css/Editor.css')
  .addStyleEntry('css/pure-css-grids', './node_modules/purecss/build/grids-min.css')
  .enableSassLoader()
  .enableVersioning(Encore.isProduction())
  .enableReactPreset()
  .enableSourceMaps(!Encore.isProduction())
  .configureBabel(function (babelConfig) {
    babelConfig.presets.push('react')
    babelConfig.presets.push('env')
    babelConfig.presets.push('stage-2')
  })
  .addPlugin(new CopyWebpackPlugin([{
    from: './app/Resources/assets/img',
    to: 'img'
  }]))
  .addPlugin(new webpack.EnvironmentPlugin({'NODE_ENV': ((Encore.isProduction) ? 'prod' : 'dev')}))

const config = Encore.getWebpackConfig()
if (Encore.isProduction()) {
  // Remove old uglify version
  config.plugins = config.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
  )
  // Add the new one
  config.plugins.push(new UglifyJsPlugin())
}
config.resolve.alias = {
  'TweenMax': path.resolve('node_modules', 'gsap/src/uncompressed/TweenMax.js'),
  'TimelineMax': path.resolve('node_modules', 'gsap/src/uncompressed/TimelineMax.js'),
  'ScrollMagic': path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/ScrollMagic.js'),
  'animation.gsap': path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js'),
  'magnific-popup-js': path.resolve('node_modules', 'magnific-popup/dist/jquery.magnific-popup.js'),
  'magnific-popup-css': path.resolve('node_modules', 'magnific-popup/src/css/main.scss')
}

module.exports = config
