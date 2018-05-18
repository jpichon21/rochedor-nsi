var Encore = require('@symfony/webpack-encore')
const UglifyJSPlugin = require('uglifyjs-webpack-plugin')
const webpack = require('webpack')

Encore
  .setOutputPath('web/assets/')
  .setPublicPath('/assets')

  .enableSourceMaps(!Encore.isProduction())

  .enableVersioning()

  .addEntry('js/main', './app/Resources/assets/js/main.js')
  .addStyleEntry('css/main', './app/Resources/assets/sass/main.scss')
  .enableSassLoader()
  .enableVersioning()
  .addPlugin(new webpack.optimize.UglifyJsPlugin({
    parallel: true
  }))
module.exports = Encore.getWebpackConfig()
