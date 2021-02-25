(function () {
    'use strict';

    var $zz = {},
        $container = {};

    $zz.service = $service;

    init();

    function init() {
        $zz.$container = $container;
        $zz.iBridge = new IBridge();
        window.zz = $zz;
    }

    function $service(name, service) {
        if (service === undefined) {
            if (!$container.hasOwnProperty(name)) {
                throw console.error('[ERROR]: service isn\'t defined yet.', name);
            }

            if ($container[name] instanceof Function) {
                $container[name] = new $container[name]();
            }

            return $container[name];
        }

        if (!(service instanceof Function)) {
            throw console.error('[ERROR]: Can\'t register service', service);
        }

        $container[name] = service;

        return $zz;
    }

    function IBridge() {
        var self = this,
            script = $('script[zz-url]'),
            iframe = $('<iframe />'),
            sender,
            services = {
                iframe: {
                    setSize: function (width, height) {
                        if (width !== undefined)
                            iframe.width(width);

                        if (height !== undefined)
                            iframe.height(height);
                    }
                },
                location: {
                    href: function (url) {
                        window.location = url;
                    }
                },
                team: {
                    avatar: function (src) {
                        $('div#bridgeTeamAvatar > img').attr('src',
                            'http://image.' + window.location.host + src
                        );
                    }
                },
                profile: {
                    loadParts: function (id) {
                        $.get(
                            '/team/profile',
                            {id: id},
                            function (response) {
                                window.history.pushState('team' + id, response.title, '/team' + id + '/');
                                $('div[square="avatar"]').html(response.avatar);
                                $('div[square="leads"]').html(response.leads);
                                $('div[square="members"]').html(response.members);
                                $('div[square="invite"]').html(response.team.category === 1 ? response.invite : '');
                                if (response.members !== null)
                                    $('div.tab_pane > a[rel="members"]').show().click();
                                else
                                    $('div.tab_pane > a[rel="members"]').hide().next().click();
                            },
                            'json'
                        );
                    }
                }
            };

        self.receive = receive;
        self.send = send;

        init();

        /**
         * Initialization
         */
        function init() {
            window.addEventListener('message', function (event) {
                var data = event.data;
                if (!(data instanceof Object) || !data.hasOwnProperty('zzToken'))
                    return;
                self.receive(data);
            }, false);

            if (script.length > 0)
                return connectByIframe();

            connectByParentWindow();
        }

        /**
         *
         */
        function connectByIframe() {
            sender = iframe
                .attr({
                    width: '100%',
                    height: '0',
                    scrolling: 'no',
                    frameborder: 0,
                    sandbox: [
                        'allow-forms',
                        'allow-scripts',
                        'allow-same-origin',
                        'allow-top-navigation'
                    ].join(' '),
                    src: (function (match, url) {
                        if (!match) {
                            throw console.error('[ERROR] ...');
                        }

                        return '&1://&2/zz/app#'
                                .replace(/&(\d)/g, function (m, i) {
                                    return match[i];
                                }) + url;
                    }).call(
                        null,
                        /(\w+):\/\/([\w\.]+)\//gi.exec(script.attr('src')),
                        script.attr('zz-url')
                    )
                })
                .insertAfter(script)
                .get(0)
                .contentWindow;
        }

        /**
         *
         */
        function connectByParentWindow() {
            sender = window.parent;
        }

        /**
         *
         */
        function receive(data) {
            var match = /(\w+)\.(\w+)/g.exec(data.service);

            if (!match)
                throw console.log('[ERROR]: ...');

            services[match[1]][match[2]].apply(self, data.inject);
        }

        /**
         *
         */
        function send(data) {
            sender.postMessage(
                Object.assign(data || {}, {zzToken: '@'}),
                '*'
            );
        }
    }

})();