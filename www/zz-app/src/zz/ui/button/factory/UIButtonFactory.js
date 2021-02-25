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