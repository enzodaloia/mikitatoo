const Encore = require('@symfony/webpack-encore');

Encore
    // Dossier où Webpack va générer les fichiers compilés
    .setOutputPath('public/build/')
    // Chemin public accessible par le navigateur
    .setPublicPath('/build')
    // Point d'entrée principal
    .addEntry('app', './assets/app.js')
    // Permet un runtime unique
    .enableSingleRuntimeChunk()
    // Nettoie le dossier build avant compilation
    .cleanupOutputBeforeBuild()
    // Active les source maps en dev
    .enableSourceMaps(!Encore.isProduction())
    // Versioning des fichiers en prod
    .enableVersioning(Encore.isProduction())
    // Permet de traiter correctement le CSS classique via PostCSS
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();