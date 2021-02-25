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

