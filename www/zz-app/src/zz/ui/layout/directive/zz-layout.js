angular
    .module('zzUi.layout')
    .directive('zzLayout', zzLayout);

zzLayout.$inject = [];

/**
 * zzLayout directive
 */
function zzLayout() {
    return {
        link: zzLayoutLink,
        restrict: 'A'
    };

    /**
     * zzLayout directive link
     */
    function zzLayoutLink(scope, element, attr) {
        var style = $('<style type="text/css" />'),
            props = ['margin', 'padding'],
            dirs = ['left', 'right', 'top', 'bottom'],
            cssDocument = props
                .map(function (prop) {
                    return dirs
                        .map(function (dir) {
                            var css = [];
                            for (var i = 0; i <= 50; i += 5) {
                                var value = [prop, dir].join('-') + ': ' + i + 'px;',
                                    name = [prop, dir]
                                        .map(function (item) {
                                            return item.substr(0, 1);
                                        })
                                        .join('');
                                css.push('.zz-' + name + '-' + i + ' { ' + value + ' } ');
                            }
                            return css.join('\n')
                        })
                        .join('\n');
                })
                .join('\n');
        $('head').append(style.html(cssDocument));
    }

    function t() {
        var dirs = ['left', 'top', 'right', 'bottom'],
            styles = {
                margin: {
                    unit: 'px',
                    dirs: dirs,
                    range: {
                        from: 0,
                        to: 50,
                        step: 5
                    }
                },
                padding: function () {
                    return this.margin;
                },
                font: {
                    size: {
                        unit: 'pt',
                        range: {
                            start: 0,
                            end: 50,
                            step: 5
                        }
                    }
                }
            };
    }
}