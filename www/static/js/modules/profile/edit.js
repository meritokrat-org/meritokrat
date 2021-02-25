(function () {

    angular
        .module('app.profile.edit', [])
        .directive('formType', [
            formType
        ])
        .controller('WorkExperienceController', [
            '$scope',
            WorkExperienceController
        ]);

    function formType() {
        return {
            link: link,
            restrict: 'A',
            scope: false
        };

        function link(scope, element) {
            if (scope.hasOwnProperty('formType')) {
                if (
                    scope.formType.hasOwnProperty('init') &&
                    scope.formType.init instanceof Function
                ) {
                    scope.formType.init(element);
                }
            }
        }
    }

    /**
     * User Work Experience controller
     * @constructor
     */
    function WorkExperienceController(scope) {
        var uiController = this,
            uiFormType = undefined;

        uiController.append = append;

        init();

        /**
         * Initialization
         */
        function init() {
            scope.formType = {
                init: initFormType
            };

            /**
             * FormType initialization
             * @param element
             */
            function initFormType(element) {
                uiFormType = element;
            }
        }

        /**
         *
         * @param event
         */
        function append(event) {
            $(uiFormType).after($(uiFormType).clone());
        }
    }

})();