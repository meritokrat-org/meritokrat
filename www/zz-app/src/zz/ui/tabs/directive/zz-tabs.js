angular
    .module('zzUi.tabs')
    .directive('zzTabs', zzTabs);

function zzTabs() {
    return {
        link: zzTabsLink,
        restrict: 'C'
    };

    function zzTabsLink(scope, element) {
        var header = $('> header', element),
            section = $('> section', element),
            tabs = $('> nav > ul > li', header),
            views = $('> section > div', element);

        $('> a', tabs)
            .click(function () {
                var tab = $(this).parent(),
                    index = tab.index(),
                    view = $(views.get(index));

                tabs.removeClass('selected');
                views.hide();

                tab.addClass('selected');
                view.show();
            })
            .first()
            .click();
    }
}