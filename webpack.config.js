const Encore = require('@symfony/webpack-encore');
const dotenv = require('dotenv');
const webpack = require('webpack');
dotenv.config();
Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js') // Main JavaScript file
    .enableReactPreset()               // Enable React support
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .addPlugin(
        new webpack.DefinePlugin({
            'process.env': JSON.stringify(process.env),
        })
    );
;

module.exports = Encore.getWebpackConfig();
