var Encore = require('@symfony/webpack-encore')

const CopyWebpackPlugin = require('copy-webpack-plugin')
const webpack = require('webpack')
const path = require('path')
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

function initEncore () {
  Encore
    .setOutputPath('web/assets/')
    .setPublicPath('/assets')
    .addEntry('js/admin/index', './app/Resources/assets/js/admin/index.js')
    .addEntry('js/main', './app/Resources/assets/js/main.js')
    .addEntry('js/home', './app/Resources/assets/js/home.js')
    .addEntry('js/page', './app/Resources/assets/js/page.js')
    .addEntry('js/speaker', './app/Resources/assets/js/speaker.js')
    .addEntry('js/calendar', './app/Resources/assets/js/calendar.js')
    .addEntry('js/calendar-api', './app/Resources/assets/js/calendar-api.js')
    .addEntry('js/gift', './app/Resources/assets/js/gift.js')
    .addEntry('js/gift-api', './app/Resources/assets/js/gift-api.js')
    .addEntry('js/calendar-registration', './app/Resources/assets/js/calendar-registration.js')
    .addEntry('js/check-validation', './app/Resources/assets/js/check-validation.js')
    .addEntry('js/editions', './app/Resources/assets/js/editions.js')
    .addEntry('js/carousel', './app/Resources/assets/js/carousel.js')
    .addEntry('js/order', './app/Resources/assets/js/order.js')
    .addEntry('js/cartcount', './app/Resources/assets/js/cartcount.js')
    .addEntry('js/form', './app/Resources/assets/js/form.js')
    .addEntry('js/password', './app/Resources/assets/js/password.js')
    .addStyleEntry('css/main', './app/Resources/assets/sass/main.scss')
    .addStyleEntry('css/home', './app/Resources/assets/sass/home.scss')
    .addStyleEntry('css/page', './app/Resources/assets/sass/page.scss')
    .addStyleEntry('css/speaker', './app/Resources/assets/sass/speaker.scss')
    .addStyleEntry('css/calendar', './app/Resources/assets/sass/calendar.scss')
    .addStyleEntry('css/gift', './app/Resources/assets/sass/gift.scss')
    .addStyleEntry('css/calendar-registration', './app/Resources/assets/sass/calendar-registration.scss')
    .addStyleEntry('css/editions', './app/Resources/assets/sass/editions.scss')
    .addStyleEntry('css/carousel', './app/Resources/assets/sass/carousel.scss')
    .addStyleEntry('css/order', './app/Resources/assets/sass/order.scss')
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
    .addPlugin(new webpack.EnvironmentPlugin({'NODE_ENV': ((Encore.isProduction) ? 'prod' : 'dev')}))
    .autoProvideVariables({
      $: 'jquery',
      jQuery: 'jquery',
      'window.jQuery': 'jquery',
      'window.$': 'jquery'
    })
}

const alias = {
  'magnific-popup-js': path.resolve('node_modules', 'magnific-popup/dist/jquery.magnific-popup.js'),
  'magnific-popup-css': path.resolve('node_modules', 'magnific-popup/src/css/main.scss'),
  'jquery-zoom-js': path.resolve('node_modules', 'jquery-zoom/jquery.zoom.js')
}

initEncore()
Encore.addPlugin(new webpack.EnvironmentPlugin({'PAYMENT_ENV': 'development'}))
const configDev = Encore.getWebpackConfig()
configDev.name = 'configDev'
if (Encore.isProduction()) {
  // Remove old uglify version
  configDev.plugins = configDev.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
  )
  // Add the new one
  configDev.plugins.push(new UglifyJsPlugin())
}
configDev.resolve.alias = alias

Encore.reset()

initEncore()
Encore.addPlugin(new webpack.EnvironmentPlugin({'PAYMENT_ENV': 'production'}))
const configProd = Encore.getWebpackConfig()
configProd.name = 'configProd'
if (Encore.isProduction()) {
  // Remove old uglify version
  configProd.plugins = configProd.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
  )
  // Add the new one
  configProd.plugins.push(new UglifyJsPlugin())
}
configProd.resolve.alias = alias

module.exports = [configDev, configProd]
