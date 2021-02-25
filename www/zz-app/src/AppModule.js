angular
    .module('zzApp', [
        /**
         * AngularJS Components
         */
        'ui.router',
        'ngMaterial',
        'ngAnimate',

        /**
         * Azzby Components
         */
        'zzUiKit',

        /**
         * Application
         */
        'TeamModule'
    ])
    .config(routerConfig);

routerConfig.$inject = ['$locationProvider'];

/**
 * Router Config
 *
 * @param locationProvider
 */
function routerConfig(locationProvider) {
    // locationProvider.html5Mode(false);
}