angular
    .module('TeamModule', [])
    .config(routerConfig);

routerConfig.$inject = ['$urlRouterProvider', '$stateProvider', '$httpProvider'];

/**
 * Config of Router
 *
 * @param urlRouterProvider
 * @param stateProvider
 */
function routerConfig(urlRouterProvider, stateProvider, $httpProvider) {
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded; charset=utf-8'
    urlRouterProvider.otherwise('/team');
    stateProvider
        .state('team', {
            url: '/team',
            abstract: true,
            template: '<div ui-view />'
        })
        .state('team.index', {
            templateUrl: '/zz/html/team/index.html',
            controller: 'IndexController'
        })
        .state('team.profile', {
            url: '/{id:int}',
            templateUrl: '/zz/html/team/profile.html'
        })
        .state('team.create', {
            url: '/create',
            templateUrl: '/zz/html/team/edit.html'
        })
        .state('team.edit', {
            url: '/edit/{id:int}',
            templateUrl: '/zz/html/team/edit.html'
        });
}
