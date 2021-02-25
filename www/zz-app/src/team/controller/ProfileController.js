angular
    .module('TeamModule')
    .controller('ProfileController', [
        '$scope', '$stateParams', '$profile',
        ProfileController
    ])
    .filter('number', function ($filter) {
        return function (input) {
            return String(input).replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        };
    });

/**
 *
 * @param $scope
 * @param $stateParams
 * @param {TeamsManager} manager
 * @constructor
 */
function ProfileController($scope, $stateParams, $profile) {
    var self = this,
        iBridge = window.zz.iBridge;

    $scope.$profile = $profile;

    self.list = $list;
    self.create = $create;
    self.edit = $edit;
    self.statLabel = $statLabel;

    init();

    function init() {
        $profile.loadTeam($stateParams.id);
    }

    function $list() {
        iBridge.send({
            service: 'location.href',
            inject: ['/team']
        });
    }

    function $create() {
        iBridge.send({
            service: 'location.href',
            inject: ['/team/create']
        });
    }

    function $edit() {
        iBridge.send({
            service: 'location.href',
            inject: ['/team/edit?id=' + $profile.getTeam().id()]
        });
    }

    function $statLabel(labelKey) {
        return ({
            l1st: 'Первинних',
            l2nd: 'Районних',
            l3rd: 'Місцевих',
            l4th: 'Регіональних'
        })[labelKey];
    }

}