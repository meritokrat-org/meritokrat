angular
    .module('TeamModule')
    .factory('TeamsManager', [
        '$http', '$q',
        'Team',
        function ($http, $q, Team) {
            var bx = {
                Request: Request
            }

            TeamsManager.prototype = {
                GET: GET
            };

            return new TeamsManager();

            /**
             *
             */
            function GET(id) {
                var req = new bx.Request('/api/team/get', {id: id}),
                    deferred = $q.defer();

                req
                    .send()
                    .then(
                        /**
                         * Success
                         * @param {Object} res
                         */
                        function (res) {
                            // @todo Need a ErrorHandler
                            deferred.resolve(new Team(res.data.entry));
                        },
                        /** Error */
                        function () {
                            deferred.reject();
                        }
                    );

                return deferred.promise;
            }


            /**
             *
             * @param id
             * @constructor
             */
            function Request(url, data, method) {
                var self = this;

                self.send = send;

                init();

                /**
                 *
                 */
                function init() {

                }

                /**
                 *
                 */
                function send() {
                    return $http({
                        method: method || 'GET',
                        url: url,
                        params: data
                    });
                }
            }
        }
    ]);

/**
 * Teams Manager
 *
 * @constructor
 */
function TeamsManager() {
    var self = this;

    self.getTeam = getTeam;
    self.getRegions = getRegions;
    self.getCities = getCities;
    self.getDistricts = getDistricts;

    init();

    /**
     * Initialization
     */
    function init() {

    }

    /**
     *
     * @param {number} id
     */
    function getTeam(id) {
        return self.GET(id);
    }

    /**
     *
     * @param {Function=} callback
     */
    function getRegions(callback) {
        http
            .get('/api/geo/getRegions', {
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
            .get('/api/geo/getCities', {
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
            .post('/api/team/find', $.param({
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
}