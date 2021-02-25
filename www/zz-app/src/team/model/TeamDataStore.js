angular
    .module('TeamModule')
    .service('$TeamDataSource', [
        '$http',
        '$filter',
        function (http, filter) {
            return new TeamDataSource(http, filter);
        }
    ]);

/**
 * Team Model
 *
 * @constructor
 */
function TeamEntity() {
    this.id = 0;
    this.title = '';
    this.level = 0;
    this.region = 0;
    this.city = 0;
    this.district = 0;
    this.dateOfAssembly = new Date();
    this.votersCount = 0;
}

/**
 *
 * @constructor
 */
function TeamDataSource(http) {
    var self = this,
        baseUrl = 'http://' + window.location.hostname + '/api/',
        entitiesMap = {};

    self.types = [];
    self.levels = [];
    self.regions = [];
    self.cities = [];
    self.districts = [];

    self.entity = entity;
    self._get = _get;
    self.get = get;
    self.save = save;
    self.getRegions = getRegions;
    self.getCities = getCities;
    self.getDistricts = getDistricts;

    self.getTeamBranch = getTeamBranch;

    init();

    /**
     * Initialization
     */
    function init() {
        self.entity('team', new TeamEntity());
        self.getRegions();

        [_types, _levels]
            .forEach(function (data) {
                self[data.name.replace(/_(\w+)/gi, '$1')] = data();
            });

        function _types() {
            return [
                {id: 1, title: 'За мiсцем проживання'},
                {id: 2, title: 'За мiсцем роботи'},
                {id: 3, title: 'За мiсцем навчання'},
                {id: 4, title: 'За професійними інтересами'},
                {id: 5, title: 'За іншими інтересами'}
            ];
        }

        function _levels() {
            return [
                {id: 5, title: 'Центральна команда'},
                {id: 4, title: 'Регіональна команда'},
                {id: 3, title: 'Місцева команда'},
                {id: 2, title: 'Районна команда'},
                {id: 1, title: 'Первинна команда'}
            ];
        }
    }

    /**
     *
     * @param alias
     * @param entity
     * @returns {*}
     */
    function entity(alias, entity) {
        if (entity !== undefined) {
            entitiesMap[alias] = entity;
        }

        return entitiesMap[alias];
    }

    /**
     *
     * @param team
     * @param callback
     * @deprecated
     */
    function _get(team, callback) {
        http
            .get(baseUrl + 'team/get', {
                params: {id: team}
            })
            .success(function (res) {
                console.log(res);
                var data = res._entry,
                    entity = self.entity('team'),
                    date = new Date();

                date.setTime(Date.parse(data.dateOfAssembly));

                entity.id = data.id;
                entity.title = data.title;
                entity.type = data.ptype;
                entity.level = data.category;
                entity.region = data.region_id;
                entity.address = data.adres;
                entity.dateOfAssembly = date;
                entity.votersCount = data.votersCount;
                entity.buildings = data.buildings;

                getCities(entity.region, function () {
                    entity.city = data.city_id;
                    getDistricts(
                        entity.region, entity.city,
                        function () {
                            entity.district = data.district_id;
                        }
                    );

                });

                if (callback !== undefined) {
                    callback.apply(self, arguments);
                }
            });
    }

    /**
     *
     * @param team
     * @param callback
     * @deprecated
     */
    function get(team, callback) {
        http
            .get(baseUrl + 'team/get', {
                params: {id: team}
            })
            .success(function (res) {
                if (!res.success) {
                    throw console.error('[ERROR]: ...');
                }

                var data = res.data,
                    entity = self.entity('team');

                ['title', 'location']
                    .forEach(function (field) {
                        entity[field] = data[field];
                    });

                if (callback instanceof Function) {
                    callback(res);
                }
            });
    }

    /**
     *
     * @param callback
     */
    function save(callback) {
        var team = self.entity('team');
        http
            .post(baseUrl + 'team/save', $.param({
                entity: Object.assign({}, team, {
                    dateOfAssembly: team.dateOfAssembly.toUTCString()
                })
            }))
            .success(function (res) {
                if (callback instanceof Function) {
                    callback.apply(self, arguments);
                }
            });
    }

    /**
     *
     * @param {Function=} callback
     */
    function getRegions(callback) {
        http
            .get(baseUrl + 'geo/getRegions', {
                params: {country: 1}
            })
            .success(function (regions) {
                self.regions = regions;
                if (callback !== undefined)
                    callback.apply(self, arguments);
            });
    }

    /**
     *
     * @param region
     * @param callback
     */
    function getCities(region, callback) {
        http
            .get(baseUrl + 'geo/getCities', {
                params: {region: region}
            })
            .success(function (list) {
                self.cities = list
                    .filter(function (item) {
                        return parseInt(item.id) > 0;
                    });
                if (callback !== undefined)
                    callback.apply(self, arguments);
            });
    }

    /**
     *
     * @param region
     * @param city
     * @param callback
     */
    function getDistricts(region, city, callback) {
        http
            .post(baseUrl + 'team/find', $.param({
                query: {
                    category: 2,
                    region: region,
                    city: city
                }
            }))
            .success(function (res) {
                self.districts = res.data;
                if (callback !== undefined)
                    callback.apply(self, arguments);
            });
    }

    /**
     * Get statistic of related teams
     *
     * @param team
     * @param callback
     */
    function getTeamBranch(team, callback) {
        http
            .get(baseUrl + 'team/getRelTeamsStat', {
                params: {id: team.id}
            })
            .success(function (res) {
                if (callback !== undefined) {
                    callback.apply(self, arguments);
                }
            });
    }
}


