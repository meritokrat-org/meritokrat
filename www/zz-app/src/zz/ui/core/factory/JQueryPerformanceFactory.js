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