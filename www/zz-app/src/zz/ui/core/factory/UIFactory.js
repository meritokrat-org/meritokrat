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

