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