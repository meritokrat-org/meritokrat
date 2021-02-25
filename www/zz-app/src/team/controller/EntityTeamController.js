angular
    .module('TeamModule');
    // .controller('EntityTeamController', CreateTeamController);

CreateTeamController.$inject = ['$scope', '$state', 'Team', 'api.geo', 'TeamsApiRegister', 'NGApp'];

/**
 * Controller of team creation
 *
 * @param scope
 * @param state
 * @param {Team} team
 * @param {ApiGeoService} api
 * @constructor
 */
function CreateTeamController(scope, state, team, api, teamRepository, ngapp) {
    var self = this;

    scope.regions = [];
    scope.cities = [];
    scope.districts = [];
    scope.levels = [
        {id: 5, title: 'Центральна команда'},
        {id: 4, title: 'Регіональна команда'},
        {id: 3, title: 'Місцева команда'},
        {id: 2, title: 'Районна команда'},
        {id: 1, title: 'Первинна команда'}
    ];
    scope.types = [
        {id: 1, title: 'За мiсцем проживання'},
        {id: 2, title: 'За мiсцем роботи'},
        {id: 3, title: 'За мiсцем навчання'},
        {id: 4, title: 'За професійними інтересами'},
        {id: 5, title: 'За іншими інтересами'}
    ];
    
    self.save = save;

    init();

    /**
     * Initialization
     */
    function init() {
        EventManager.prototype = {
            scope: scope,
            team: team,
            teamRepository: teamRepository,
            api: api
        };

        self.eventManager = new EventManager();

        scope.team = team;
    }

    /**
     *
     */
    function save() {
        teamRepository.save(team, function (id) {
            ngapp.send({
                service: 'ngapp',
                fn: 'go',
                args: ['/team' + id + '/1']
            }, function () {
                
            });
        });
    }
}

/**
 * Event Manager
 *
 * @constructor
 */
function EventManager() {
    var self = this,
        scope = self.scope,
        team = self.team,
        teamRepository = self.teamRepository,
        api = self.api;

    self.onRegionInit = onRegionInit;
    self.onRegionChange = onRegionChange;
    self.onCityChange = onCityChange;
    self.onDistrictChange = onDistrictChange;

    /**
     * Invoke in case when init region element
     */
    function onRegionInit() {
        api.getRegions(function (regions) {
            scope.regions = [{id: 0, title: '\u2014'}].concat(regions);
            scope.$apply();
        });
    }

    /**
     * Invoke when region was changed
     */
    function onRegionChange() {
        console.log(team);
        api.getCities(
            team.region,
            function (cities) {
                scope.cities = [{id: 0, title: '\u2014'}].concat(cities);
            }
        );
    }

    /**
     * Invoke when city was changed
     */
    function onCityChange() {
        console.log(team);
        teamRepository.find({
            query: {
                category: 2,
                region: team.region,
                city: team.city
            }
        }, function (list) {
            scope.districts = list;
        });
    }

    function onDistrictChange() {
        console.log(team);
    }
}
