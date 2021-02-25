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
