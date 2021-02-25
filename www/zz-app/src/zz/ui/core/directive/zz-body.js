angular
    .module('zzUi.core')
    .directive('zzBody', [
        zzBody
    ]);

function zzBody() {
    return {
        link: zzBodyLink,
        restrict: 'A'
    };

    function zzBodyLink() {
        var iBridge = window.zz.iBridge,
            bodyHeight = $('body > *').innerHeight();
        var interval = setInterval(function () {
            if (bodyHeight !== $('body > *').innerHeight()) {
                bodyHeight = $('body > *').innerHeight();
                iBridge.send({
                    service: 'iframe.setSize',
                    inject: [undefined, bodyHeight]
                });
            }
        }, 250);
    }
}