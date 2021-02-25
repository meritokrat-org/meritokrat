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