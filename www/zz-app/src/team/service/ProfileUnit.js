angular
    .module('TeamModule')
    .service('$profile', [
        'TeamsManager',
        function (TeamsManager) {
            return new ProfileUnit(TeamsManager);
        }
    ]);

/**
 *
 * @constructor
 */
function ProfileUnit(TeamsManager) {
    var self = this,
        iBridge = window.zz.iBridge,
        currentTeam = undefined,
        historyStack = [];

    self.getTeam = $getTeam;
    self.setTeam = $setTeam;
    self.loadTeam = $loadTeam;

    self.stack = $stack;
    self.stepBack = $stepBack;

    init();

    /**
     * Initialization
     */
    function init() {

    }

    /**
     *
     * @returns {undefined}
     */
    function $getTeam() {
        return currentTeam;
    }

    /**
     *
     * @param team
     */
    function $setTeam(team) {
        if (currentTeam !== undefined) {
            historyStack.push(currentTeam);
        }

        currentTeam = team;

        return self;
    }

    /**
     *
     * @param id
     */
    function $loadTeam(id, callback) {
        TeamsManager
            .getTeam(id)
            .then(function (team) {
                self.setTeam(team);
                iBridge.send({
                    service: 'profile.loadParts',
                    inject: [team.id()]
                });
                if (callback instanceof Function) {
                    callback(team);
                }
            });
    }

    /**
     *
     */
    function $stack() {
        return historyStack;
    }

    /**
     *
     */
    function $isHistoryStackEmpty() {

    }

    /**
     *
     */
    function $stepBack() {
        if (!(historyStack.length > 0)) {
            throw console.error('[ERROR]: stack of transitions history is empty.');
        }

        currentTeam = historyStack.pop();
        iBridge.send({
            service: 'profile.loadParts',
            inject: [currentTeam.id()]
        });
    }

}