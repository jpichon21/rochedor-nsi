var Encore = require('@symfony/webpack-encore')

const CopyWebpackPlugin = require('copy-webpack-plugin')
const webpack = require('webpack')
const path = require('path')

Encore
  .setOutputPath('web/assets/')
  .setPublicPath('/assets')
  .addEntry('js/main', './app/Resources/assets/js/main.js')
  .addEntry('js/home', './app/Resources/assets/js/home.js')
  .addEntry('js/page', './app/Resources/assets/js/page.js')
  .addStyleEntry('css/main', './app/Resources/assets/sass/main.scss')
  .addEntry('js/admin/index', './app/Resources/assets/js/admin/index.js')
  .addStyleEntry('css/Draft/Draft', './app/Resources/assets/css/Draft.css')
  .addStyleEntry('css/Draft/Editor', './app/Resources/assets/css/Editor.css')
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

if (Encore.isProduction()) {
  Encore.addPlugin(new webpack.optimize.UglifyJsPlugin({
    parallel: true
  }))
}

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
