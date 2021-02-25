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