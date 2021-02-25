angular
    .module('zzUi.layout', [])
    .run([
        'zzCSSHelpersFactory',
        function (zzCSSHelpersFactory) {
            zzCSSHelpersFactory.create();
        }
    ]);