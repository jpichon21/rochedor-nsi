var Encore = require('@symfony/webpack-encore')

const CopyWebpackPlugin = require('copy-webpack-plugin')
const webpack = require('webpack')
const path = require('path')

Encore
  .setOutputPath('web/assets/')
  .setPublicPath('/assets')
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning()
  .addEntry('js/main', './app/Resources/assets/js/main.js')
  .addEntry('js/home', './app/Resources/assets/js/home.js')
  .addEntry('js/page', './app/Resources/assets/js/page.js')
  .addEntry('js/calendar', './app/Resources/assets/js/calendar.js')
  .addStyleEntry('css/main', './app/Resources/assets/sass/main.scss')
  .addStyleEntry('css/calendar', './app/Resources/assets/sass/calendar.scss')
  .enableSassLoader()
  .enableVersioning()
  .addPlugin(new webpack.optimize.UglifyJsPlugin({
    parallel: true
  }))
  .addPlugin(new CopyWebpackPlugin([{
    from: './app/Resources/assets/img',
    to: 'img'
  }]))

const config = Encore.getWebpackConfig()

config.resolve.alias = {
  'TweenMax': path.resolve('node_modules', 'gsap/src/uncompressed/TweenMax.js'),
  'TimelineMax': path.resolve('node_modules', 'gsap/src/uncompressed/TimelineMax.js'),
  'ScrollMagic': path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/ScrollMagic.js'),
  'animation.gsap': path.resolve('node_modules', 'scrollmagic/scrollmagic/uncompressed/plugins/animation.gsap.js'),
  'magnific-popup-js': path.resolve('node_modules', 'magnific-popup/dist/jquery.magnific-popup.js'),
  'magnific-popup-css': path.resolve('node_modules', 'magnific-popup/src/css/main.scss')
}

module.exports = config
