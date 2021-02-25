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