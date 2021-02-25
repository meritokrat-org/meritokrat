angular
    .module('zzUi.panel')
    .factory('UIPanelFactory', [
        'UIFactory',
        function (UIFactory) {
            return UIPanelFactory;
        }
    ]);

/**
 * Factory of UIPanel
 *
 * @constructor
 * @extends {UIFactory}
 */
function UIPanelFactory() {

    var self = this;

    self.getPerformance = getPerformance;

    init();

    /**
     *
     */
    function init() {

    }

    function getPerformance(zzPanel) {
        return jQueryPerformance(zzPanel);
    }

}

/**
 *
 * @constructor
 */
function UIPanel() {

}

/**
 *
 * @returns {*}
 */
function jQueryPerformance(zzPanel) {
    return init();

    function init() {
        return panel();
    }

    function panel() {
        return $('<div class="zz-ui-panel" />')
            .append(
                header(),
                content()
            );
    }

    function header() {
        return $('<header />').append(
            $('<ul />')
                .append(
                    $('<li />').append($('<h4 />').html('Panel #1'))
                )
                .append(
                    $('<li />').append(
                        $('<a class="zz-button zz-icon-button zz-arrow-up zz-fx" />')
                            .click(arrowClickEvent)
                            .append(
                                $('<i class="ion-chevron-up zz-fx" />')
                            )
                    )
                )
        );

        function arrowClickEvent() {
            if (!$(this).hasClass('zz-arrow-down'))
                $(this).addClass('zz-arrow-down');
            else
                $(this).removeClass('zz-arrow-down');
        }
    }

    function content() {
        return $('<section />').append($(zzPanel).find('> *'));
    }

}