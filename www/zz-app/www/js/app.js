;(function() {
"use strict";

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
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

angular
    .module('zzUi', [
        'zzUi.core',
        'zzUi.layout',
        'zzUi.button',
        'zzUi.tabs',
        'zzUi.panel',
        'zzUi.paginator'
    ]);
}());

;(function() {
"use strict";

angular
    .module('zzUiKit', ['zzUi']);
}());

;(function() {
"use strict";

angular
    .module('zzUi.button', []);
}());

;(function() {
"use strict";

angular
    .module('zzUi.core', []);
}());

;(function() {
"use strict";

angular
    .module('zzUi.layout', [])
    .run([
        'zzCSSHelpersFactory',
        function (zzCSSHelpersFactory) {
            zzCSSHelpersFactory.create();
        }
    ]);
}());

;(function() {
"use strict";

angular
    .module('zzUi.paginator', []);
}());

;(function() {
"use strict";

angular
    .module('zzUi.panel', []);
}());

;(function() {
"use strict";

angular
    .module('zzUi.tabs', []);
}());

;(function() {
"use strict";

angular
    .module('TeamModule')
    .factory('Team', [
        function () {
            return Team;
        }
    ]);

/**
 * Team Entity
 *
 * @constructor
 */
function Team(data) {
    var self = this,
        hashMap = {};

    hashMap.id = null;
    hashMap.title = '';
    hashMap.avatar = '';
    hashMap.location = {};
    hashMap.stat = {};

    self.$hashMap = hashMap;
    self.id = $id;
    self.title = $title;
    self.location = $location;
    self.branch = $branch;
    self.stat = $stat;
    self.avatar = $avatar;
    self.buildings = $buildings;

    init();

    /**
     * Initialization
     */
    function init() {
        if (data === undefined)
            return;
        ['id', 'title', 'location', 'branch', 'stat', 'avatar', 'buildings']
            .forEach(function (prop) {
                if (!data.hasOwnProperty(prop))
                    return;
                self[prop].call(self, data[prop]);
            })
    }

    /**
     * Getter|Setter of Team.Id
     *
     * @param {number=} id
     * @returns {number|Team}
     */
    function $id(id) {
        if (id === undefined) {
            return hashMap.id;
        }

        hashMap.id = parseInt(id);

        return self;
    }

    /**
     * Getter|Setter of Team.Title
     *
     * @param {string=} id
     * @returns {string|Team}
     */
    function $title(title) {
        if (title === undefined) {
            return hashMap.title;
        }

        hashMap.title = title;

        return self;
    }

    /**
     * Getter|Setter of Team.Location
     *
     * @param {Object=} location
     * @returns {Object|Team}
     */
    function $location(location) {
        if (location === undefined) {
            return hashMap.location;
        }

        hashMap.location = location;

        return self;
    }

    /**
     * Getter|Setter of Team.Branch
     *
     * @param {Object=} branch
     * @returns {Object|Team}
     */
    function $branch(branch) {
        if (branch === undefined) {
            return hashMap.branch;
        }

        hashMap.branch = branch;

        return self;
    }

    /**
     * Getter|Setter of Team.Stat
     *
     * @param {Object=} branch
     * @returns {Object|Team}
     */
    function $stat(stat) {
        if (stat === undefined) {
            return hashMap.stat;
        }

        hashMap.stat = stat;

        return self;
    }

    /**
     *
     */
    function $avatar(avatar) {
        if (avatar === undefined) {
            return hashMap.avatar;
        }

        hashMap.avatar = avatar;

        return self;
    }

    /**
     * 
     * @param buildings
     * @returns {*}
     */
    function $buildings(buildings) {
        if (buildings === undefined) {
            return hashMap.buildings;
        }

        hashMap.buildings = buildings;

        return self;
    }
}
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

angular
    .module('zzUi.button')
    .factory('uiButtonFactory', [
        '$compile',
        function (compile) {
            UIButtonFactory.prototype = {
                compiler: compile
            };

            return new UIButtonFactory();
        }
    ]);

/**
 * UI Button Factory
 *
 * @constructor
 */
function UIButtonFactory() {
    var $self = this,
        compiler = $self.compiler;

    $self.configure = configure;

    /**
     * Set ui.Button configuration
     *
     * @param {Object=} config
     * @returns {{
     *  config: Object,
     *  createView: createView
     * }}
     */
    function configure(config) {
        return {
            config: config || {},
            createView: createView
        };
    }

    /**
     * Create Element by Element, which come from Directive
     *
     * @returns {{draw: draw}}
     */
    function createView() {
        var uiButtonRender = new UIButtonRender(this.config);

        return {
            replaceView: this.config.replaceView,
            buttonView: uiButtonRender.render(),
            draw: draw
        };
    }

    /**
     *
     */
    function draw(scope) {
        return compiler(this.performance)(scope);
    }
}

/**
 * UI Button Render
 *
 * @constructor
 */
function UIButtonRender(config) {
    var $self = this,
        $this = {},
        uiConfig = {
            replaceElement: false,
            buttonText: false,
            iconButton: false
        };

    // private methods
    $this.createView = createView;

    // public methods
    $self.render = render;

    init();

    /**
     * Initialization
     */
    function init() {
        Object.assign($this, $self);

        if (!config instanceof Object)
            return;

        Object
            .keys(uiConfig)
            .forEach(function (option) {
                if (
                    !config.hasOwnProperty(option) ||
                    config[option] === undefined
                )
                    return;

                uiConfig[option] = config[option];
            });
    }

    /**
     * Render view
     */
    function render() {
        self.createView();
    }

    /**
     *
     * @returns {*|jQuery|HTMLElement}
     */
    function createView() {
        var buttonView = $('<button class="zz-button" />');

        if (uiConfig.iconButton) {
            buttonView
                .addClass('zz-icon-button')
                .append(iconButtonView(uiConfig.iconButton));
        }

        return buttonView;

        /**
         *
         * @param iconButton
         * @returns {*|jQuery}
         */
        function iconButtonView(iconButton) {
            return $('<i />').addClass(iconButton);
        }
    }

    /**
     *
     */
    function getJQObject() {
        return jQObject;
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.core')
    .factory('JQueryPerformanceFactory', function () {
        return JQueryPerformanceFactory;
    });

/**
 * JQuery Performance Factory
 *
 * @constructor
 */
function JQueryPerformanceFactory() {
    var self = this,
        JQPerformance = new JQueryPerformance();

    self.register = JQPerformance.register;
    self.create = create;

    init();

    /**
     * Initialization
     */
    function init() {

    }

    /**
     * Create jQuery Performance
     *
     * @returns {jQuery}
     */
    function create() {
        return JQPerformance.create('performance');
    }
}

/**
 * JQuery DI Manager
 *
 * @constructor
 */
function JQueryPerformance() {
    var self = this,
        container = {},
        deps = {};

    self.register = register;
    self.create = create;

    /**
     * Register part of performance
     *
     * @param depName
     * @param dep
     * @returns {JQueryPerformance}
     */
    function register(depName, dep) {
        deps[depName] = dep;

        return self;
    }

    /**
     * Get instance of dependency by dependency name
     *
     * @param {string} depName
     * @returns {jQuery}
     */
    function create(depName) {
        return getPartsTogether(depName);
    }

    /**
     * Get parts of performance together
     *
     * @param {string} depName
     * @param {(Array|Function)=} dep
     * @param {Array=} injection
     * @returns {*}
     */
    function getPartsTogether(depName, dep, injection) {
        if (container.hasOwnProperty(depName)) {
            return container[depName];
        }

        if (dep === undefined) {
            return getPartsTogether(depName, deps[depName]);
        }

        if (dep instanceof Function) {
            return container[depName] = dep.apply(null, injection);
        }

        injection = dep
            .slice(0, -1)
            .map(function (depName) {
                return getPartsTogether(depName, deps[depName]);
            });

        return getPartsTogether(depName, dep[dep.length - 1], injection);
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.core')
    .factory('UIFactory', [
        'JQueryPerformanceFactory',
        function (JQueryPerformanceFactory) {
            UIFactory.prototype = {
                JQueryPerformanceFactory: JQueryPerformanceFactory
            };
            
            return UIFactory;
        }
    ]);

/**
 * Abstract UI Factory
 *
 * @constructor
 */
function UIFactory() {
    var self = this,
        uiHandler,
        performanceFactory;

    self.getUIHandler = getUIHandler;
    self.setUIHandler = setUIHandler;
    self.setPerformanceFactory = setPerformanceFactory;
    self.getPerformance = getPerformance;

    init();

    /**
     * Initialization
     */
    function init() {
        performanceFactory = new self.JQueryPerformanceFactory();
    }

    /**
     *
     * @returns {*}
     */
    function getUIHandler() {
        return uiHandler;
    }

    /**
     *
     */
    function setUIHandler(UIHandler) {
        uiHandler = new UIHandler;

        return self;
    }

    /**
     * Get jQuery Performance
     *
     * @returns {jQuery}
     */
    function getPerformance() {
        return performanceFactory.create();
    }

    /**
     * Set Performance Factory
     * 
     * @param {Function} Factory
     */
    function setPerformanceFactory(Factory) {
        performanceFactory = new Factory();

        return self;
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.core')
    .factory('zzCSSHelpersFactory', [
        zzCSSHelpersFactory
    ]);

function zzCSSHelpersFactory() {
    var cssHelpers = zzCSSHelpers();

    return {
        create: create
    };

    function create() {
        return cssHelpers.compose();
    }
}

function zzCSSHelpers() {
    var self = {collection: [], attach: _attach, compose: _compose},
        dirsSeries = ['left', 'top', 'right', 'bottom'],
        valsRange = {from: 0, to: 50, step: 5};

    init();

    return {
        compose: function () {
            return self.compose.apply(null, self.collection);
        }
    };

    /**
     * Initialization
     */
    function init() {
        self.attach(_composeLayout);
    }

    /**
     * Attach assembly
     *
     * @param assembly
     * @private
     */
    function _attach(assembly) {
        this.collection.push(assembly);

        return this;
    }

    /**
     * Compose assembly
     *
     * @returns {Array}
     * @private
     */
    function _compose() {
        var args = arguments,
            assembly = [];
        return Object
                .keys(args)
                .forEach(function (i) {
                    assembly = assembly.concat(args[i].call());
                }) || assembly;
    }

    /**
     * Compose layout assembly
     *
     * @returns {*}
     * @private
     */
    function _composeLayout() {
        return self.compose(align, margin, padding);

        function align() {
            return ['align-1', 'align-2', 'align-3'];
        }

        function margin() {
            return ['margin-1', 'margin-2', 'margin-3'];
        }

        function padding() {
            return ['padding-1', 'padding-2', 'padding-3'];
        }
    }

    /**
     *
     * @private
     */
    function _fontHelper() {


        function size() {

        }

        function weight() {

        }
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.paginator')
    .factory('UIPaginatorFactory', [
        'UIFactory',
        function (UIFactory) {
            UIPaginatorFactory.prototype = new UIFactory();

            return UIPaginatorFactory;
        }
    ]);

/**
 * Factory of UI.Paginator
 *
 * @constructor
 * @extends {UIFactory}
 */
function UIPaginatorFactory() {
    var self = this;

    JQueryPaginatorPerformanceFactory.prototype = new self.JQueryPerformanceFactory();

    init();

    /**
     * Initialization
     */
    function init() {
        self
            .setUIHandler(UIPaginator)
            .setPerformanceFactory(JQueryPaginatorPerformanceFactory);
    }
}

/**
 *
 * @constructor
 */
function UIPaginator() {
    var self = this,
        events = {},
        databox = {
            rowsCount: 0,
            perPage: 10,
            currentPage: 1
        };

    self.pages = [];

    self.assign = assign;
    self.currentPage = currentPage;
    self.isCurrentPage = isCurrentPage;
    self.perPage = perPage;
    self.rowsCount = rowsCount;

    self.onChange = onChange;

    init();

    function init() {

    }

    function assign(data) {
        databox = data;
        _flush();
    }

    function currentPage(number, event) {
        if (number === undefined)
            return databox.currentPage;

        var c = databox.currentPage,
            n = number < 1 ? 1 : number;

        databox.currentPage = n;
        _flush();

        if(c !== n && events.hasOwnProperty('on-change'))
            events['on-change'](databox);

        return self;
    }

    function isCurrentPage(number) {
        return !!(self.currentPage() == number)
    }

    function perPage(value) {
        if (value === undefined)
            return databox.perPage;

        databox.perPage = value;
        _flush();

        return self;
    }

    function rowsCount(value) {
        if (value === undefined)
            return databox.rowsCount;

        databox.rowsCount = value;
        _flush();

        return self;
    }

    function onChange(callback) {
        events['on-change'] = callback;
    }

    /**
     *
     * @returns {*}
     * @private
     */
    function _flush() {
        self.pages = [];
        for (var i = 0; i < Math.ceil(databox.rowsCount / databox.perPage); i++) {
            self.pages.push({
                number: (i + 1),
                isCurrent: function () {
                    return isCurrentPage(this.number);
                }
            });
        }
    }
}

/**
 * JQuery Performance Factory of ui.Paginator
 *
 * @constructor
 * @extends {JQueryPerformanceFactory}
 */
function JQueryPaginatorPerformanceFactory() {
    var self = this;

    init();

    /**
     * Initialization
     */
    function init() {
        self
            .register('performance', ['ul', performance])
            .register('ul', ['li', ul])
            .register('li', li);
    }

    /**
     * Create jquery paginator performance
     *
     * @param {jQuery} wrapper
     * @returns {jQuery}
     * @private
     */
    function performance(wrapper) {
        return $('<nav class="zz-ui-paginator" />').append(wrapper);
    }

    /**
     * Create wrapper of items
     *
     * @param {jQuery} item
     * @returns {jQuery}
     * @private
     */
    function ul(item) {
        return $('<ul class="wrapper" />').append(item);
    }

    /**
     * Create paginator selector
     *
     * @returns {jQuery}
     * @private
     */
    function li() {
        return $('<li class="page" />')
            .attr('ng-repeat', 'page in UI.pages')
            .attr('ng-class', '{"current": page.isCurrent()}')
            .attr('ng-click', 'UI.currentPage(page.number)')
            .attr('pos', '{{ page.number }}')
            .append(
                $('<a class="zz-ui-button zz-fx" />').html('{{ page.number }}')
            );
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.panel')
    .factory('UIPanelFactory', [
        'UIFactory',
        function (UIFactory) {
            return UIPanelFactory;
        }
    ]);

/**
 * Factory of UIPanel
 *
 * @constructor
 * @extends {UIFactory}
 */
function UIPanelFactory() {

    var self = this;

    self.getPerformance = getPerformance;

    init();

    /**
     *
     */
    function init() {

    }

    function getPerformance(zzPanel) {
        return jQueryPerformance(zzPanel);
    }

}

/**
 *
 * @constructor
 */
function UIPanel() {

}

/**
 *
 * @returns {*}
 */
function jQueryPerformance(zzPanel) {
    return init();

    function init() {
        return panel();
    }

    function panel() {
        return $('<div class="zz-ui-panel" />')
            .append(
                header(),
                content()
            );
    }

    function header() {
        return $('<header />').append(
            $('<ul />')
                .append(
                    $('<li />').append($('<h4 />').html('Panel #1'))
                )
                .append(
                    $('<li />').append(
                        $('<a class="zz-button zz-icon-button zz-arrow-up zz-fx" />')
                            .click(arrowClickEvent)
                            .append(
                                $('<i class="ion-chevron-up zz-fx" />')
                            )
                    )
                )
        );

        function arrowClickEvent() {
            if (!$(this).hasClass('zz-arrow-down'))
                $(this).addClass('zz-arrow-down');
            else
                $(this).removeClass('zz-arrow-down');
        }
    }

    function content() {
        return $('<section />').append($(zzPanel).find('> *'));
    }

}
}());

;(function() {
"use strict";

angular
    .module('zzUiKit')
    .service('$zzUiKit', UIToolkitManager);

/**
 *
 * @constructor
 */
function UIToolkitManager() {
    var self = this,
        _collection = {};

    self.paginator = paginator;

    init();

    /**
     * Initialization
     */
    function init() {

    }

    /**
     * Get Paginator Builder
     *
     * @param {Object=} options
     * @returns {UIPaginatorBuilder}
     */
    function paginator(options) {
        
    }

}
}());

;(function() {
"use strict";

angular
    .module('zzUi.layout')
    .service('zzLayoutManager', LayoutManager);

LayoutManager.$inject = [];

/**
 * zzLayout directive
 */
function LayoutManager() {
    
}
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

angular
    .module('TeamModule')
    .controller('EditController', EditController);

EditController.$inject = ['$scope', '$stateParams', '$TeamDataSource'];

/**
 * Edit Controller
 *
 * @constructor
 */
function EditController(scope, params, dataSource) {
    var self = this,
        panelFactory = new PanelFactory({main: {header: false}});

    self.panel = panel;
    self.dataSource = dataSource;
    self.entity = dataSource.entity;
    self.save = save;
    self.onSavedSuccess = false;
    self.onSavedError = false;

    // Events
    self.onRegionChange = onRegionChange;
    self.onCityChange = onCityChange;

    init();

    function init() {
        if (params.id > 0) {
            self.dataSource._get(params.id);
        }
    }

    /**
     * Get Panel
     *
     * @param alias
     * @returns {Panel}
     */
    function panel(alias) {
        return panelFactory.get(alias);
    }

    function save() {
        self.dataSource.save(function (res) {
            if (
                !res.hasOwnProperty('success')
                || true !== !!res.success
            )
                return self.onSavedError = true;

            self.onSavedSuccess = true;
        });
    }

    // Events
    function onRegionChange() {
        self.dataSource.getCities(
            self.entity('team').region,
            function () {

            }
        );
    }

    function onCityChange() {
        self.dataSource.getDistricts(
            self.entity('team').region,
            self.entity('team').city,
            function () {

            }
        );
    }
}

/**
 * Panel Factory
 *
 * @param {Object} context
 * @constructor
 */
function PanelFactory(context) {
    var self = this,
        panelsMap = {};

    self.create = create;
    self.get = get;

    init();

    /**
     * Initialization
     */
    function init() {
        if (context !== undefined) {
            Object
                .keys(context)
                .forEach(function (alias) {
                    self.create(alias, context[alias]);
                });
        }
    }

    /**
     * Create Panel
     *
     * @param {string} alias
     * @param {object} context
     * @returns {Panel}
     */
    function create(alias, context) {
        return (panelsMap[alias] = new Panel(context));
    }

    /**
     * Get Panel
     *
     * @param {string} alias
     * @returns {Panel}
     */
    function get(alias) {
        if (!panelsMap.hasOwnProperty(alias))
            throw console.error('[ERROR]: ...');

        return panelsMap[alias];
    }

    /**
     * Panel
     *
     * @constructor
     */
    function Panel(context) {
        var self = this;

        self.header = true;

        init();

        /**
         * Initialization
         */
        function init() {
            if (context !== undefined) {
                Object
                    .keys(context)
                    .forEach(function (key) {
                        self[key] = context[key];
                    });
            }
        }
    }
}
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

angular
    .module('TeamModule')
    .controller('IndexController', IndexController);

IndexController.$inject = ['$scope', '$state'];

/**
 * Index Controller of Team module
 * 
 * @constructor
 */
function IndexController(scope, state) {
   
    var self = this;
    
}
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

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
}());

;(function() {
"use strict";

angular
    .module('zzUi.button')
    .directive('zzButton', [
        'uiButtonFactory',
        zzButton
    ]);

/**
 * 
 * @returns {{link: zzButtonLink, restrict: string}}
 */
function zzButton(uiButtonFactory) {
    return {
        link: zzButtonLink,
        restrict: 'E',
        scope: {
            iconButton: '@?zzIconButton'
        }
    };

    /**
     * Directive Link
     *
     * @param scope
     * @param element
     */
    function zzButtonLink(scope, element) {
        uiButtonFactory
            .configure({
                replaceElement: element,
                iconButton: scope.iconButton
            })
            .createView()
            .draw(scope);
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.core')
    .directive('zzBody', [
        zzBody
    ]);

function zzBody() {
    return {
        link: zzBodyLink,
        restrict: 'A'
    };

    function zzBodyLink() {
        var iBridge = window.zz.iBridge,
            bodyHeight = $('body > *').innerHeight();
        var interval = setInterval(function () {
            if (bodyHeight !== $('body > *').innerHeight()) {
                bodyHeight = $('body > *').innerHeight();
                iBridge.send({
                    service: 'iframe.setSize',
                    inject: [undefined, bodyHeight]
                });
            }
        }, 250);
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.layout')
    .directive('zzLayout', zzLayout);

zzLayout.$inject = [];

/**
 * zzLayout directive
 */
function zzLayout() {
    return {
        link: zzLayoutLink,
        restrict: 'A'
    };

    /**
     * zzLayout directive link
     */
    function zzLayoutLink(scope, element, attr) {
        var style = $('<style type="text/css" />'),
            props = ['margin', 'padding'],
            dirs = ['left', 'right', 'top', 'bottom'],
            cssDocument = props
                .map(function (prop) {
                    return dirs
                        .map(function (dir) {
                            var css = [];
                            for (var i = 0; i <= 50; i += 5) {
                                var value = [prop, dir].join('-') + ': ' + i + 'px;',
                                    name = [prop, dir]
                                        .map(function (item) {
                                            return item.substr(0, 1);
                                        })
                                        .join('');
                                css.push('.zz-' + name + '-' + i + ' { ' + value + ' } ');
                            }
                            return css.join('\n')
                        })
                        .join('\n');
                })
                .join('\n');
        $('head').append(style.html(cssDocument));
    }

    function t() {
        var dirs = ['left', 'top', 'right', 'bottom'],
            styles = {
                margin: {
                    unit: 'px',
                    dirs: dirs,
                    range: {
                        from: 0,
                        to: 50,
                        step: 5
                    }
                },
                padding: function () {
                    return this.margin;
                },
                font: {
                    size: {
                        unit: 'pt',
                        range: {
                            start: 0,
                            end: 50,
                            step: 5
                        }
                    }
                }
            };
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.paginator')
    .directive('zzPaginator', zzPaginator);

zzPaginator.$inject = ['UIPaginatorFactory', '$compile'];

/**
 * Directive of zz-paginator
 *
 * @param {Function} UIFactory
 * @param $compile
 * @returns {{link: zzPaginatorLink, restrict: string}}
 */
function zzPaginator(UIFactory, $compile) {
    return {
        link: zzPaginatorLink,
        restrict: 'E',
        scope: {
            data: '=',
            onChange: '='
        }
    }

    /**
     * Directive Link
     */
    function zzPaginatorLink(scope, element) {
        var uiFactory = new UIFactory(),
            UI = scope.UI = uiFactory.getUIHandler();

        UI.onChange(scope.onChange);

        element.replaceWith($compile((new UIFactory()).getPerformance())(scope));

        scope.$watch('data', function (data) {
            UI.assign(data);
        }, true);
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.panel')
    .directive('zzPanel', zzPanel);

zzPanel.$inject = ['UIPanelFactory', '$compile'];

/**
 *
 */
function zzPanel(UIPanelFactory, compile) {
    return {
        link: zzPanelLink,
        restrict: 'E'
    };

    /**
     *
     */
    function zzPanelLink(scope, element) {
        var uiBuilder = new UIPanelFactory(),
            performance = uiBuilder.getPerformance(element);

        element.replaceWith(compile(performance)(scope));
    }
}
}());

;(function() {
"use strict";

angular
    .module('zzUi.tabs')
    .directive('zzTabs', zzTabs);

function zzTabs() {
    return {
        link: zzTabsLink,
        restrict: 'C'
    };

    function zzTabsLink(scope, element) {
        var header = $('> header', element),
            section = $('> section', element),
            tabs = $('> nav > ul > li', header),
            views = $('> section > div', element);

        $('> a', tabs)
            .click(function () {
                var tab = $(this).parent(),
                    index = tab.index(),
                    view = $(views.get(index));

                tabs.removeClass('selected');
                views.hide();

                tab.addClass('selected');
                view.show();
            })
            .first()
            .click();
    }
}
}());
