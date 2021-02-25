angular
    .module('TeamModule')
    .controller('TeamBranchController', [
        '$scope', '$profile',
        TeamBranchController
    ]);

/**
 * Controller of team branch
 *
 * @param $scope
 * @constructor
 */
function TeamBranchController($scope, $profile) {
    var self = this,
        pastTeams = [],
        iBridge = window.zz.iBridge;

    $scope.$branch = undefined;

    self.setLevelUp = setLevelUp;
    self.setLevelDown = setLevelDown;
    self.getJumpPath = getJumpPath;
    self.refresh = refresh;

    init();

    /**
     * Initialization
     */
    function init() {

    }

    /**
     * Return to the upper level of current branch
     *
     * @param {ProfileController} profile
     */
    function setLevelUp() {
        $profile.stepBack();
    }

    /**
     * Show next level of a current branch
     *
     * @param child
     */
    function setLevelDown(child) {
        $profile.loadTeam(child.id);
    }

    /**
     *
     */
    function refresh() {
    }

    /**
     *
     */
    function getJumpPath() {
        return [];
    }
}