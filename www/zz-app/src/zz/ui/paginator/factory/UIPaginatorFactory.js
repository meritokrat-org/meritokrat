angular
    .module('zzUi.paginator')
    .factory('UIPaginatorFactory', [
        'UIFactory',
        function (UIFactory) {
            UIPaginatorFactory.prototype = new UIFactory();

            return UIPaginatorFactory;
        }
    ]);

/**
 * Factory of UI.Paginator
 *
 * @constructor
 * @extends {UIFactory}
 */
function UIPaginatorFactory() {
    var self = this;

    JQueryPaginatorPerformanceFactory.prototype = new self.JQueryPerformanceFactory();

    init();

    /**
     * Initialization
     */
    function init() {
        self
            .setUIHandler(UIPaginator)
            .setPerformanceFactory(JQueryPaginatorPerformanceFactory);
    }
}

/**
 *
 * @constructor
 */
function UIPaginator() {
    var self = this,
        events = {},
        databox = {
            rowsCount: 0,
            perPage: 10,
            currentPage: 1
        };

    self.pages = [];

    self.assign = assign;
    self.currentPage = currentPage;
    self.isCurrentPage = isCurrentPage;
    self.perPage = perPage;
    self.rowsCount = rowsCount;

    self.onChange = onChange;

    init();

    function init() {

    }

    function assign(data) {
        databox = data;
        _flush();
    }

    function currentPage(number, event) {
        if (number === undefined)
            return databox.currentPage;

        var c = databox.currentPage,
            n = number < 1 ? 1 : number;

        databox.currentPage = n;
        _flush();

        if(c !== n && events.hasOwnProperty('on-change'))
            events['on-change'](databox);

        return self;
    }

    function isCurrentPage(number) {
        return !!(self.currentPage() == number)
    }

    function perPage(value) {
        if (value === undefined)
            return databox.perPage;

        databox.perPage = value;
        _flush();

        return self;
    }

    function rowsCount(value) {
        if (value === undefined)
            return databox.rowsCount;

        databox.rowsCount = value;
        _flush();

        return self;
    }

    function onChange(callback) {
        events['on-change'] = callback;
    }

    /**
     *
     * @returns {*}
     * @private
     */
    function _flush() {
        self.pages = [];
        for (var i = 0; i < Math.ceil(databox.rowsCount / databox.perPage); i++) {
            self.pages.push({
                number: (i + 1),
                isCurrent: function () {
                    return isCurrentPage(this.number);
                }
            });
        }
    }
}

/**
 * JQuery Performance Factory of ui.Paginator
 *
 * @constructor
 * @extends {JQueryPerformanceFactory}
 */
function JQueryPaginatorPerformanceFactory() {
    var self = this;

    init();

    /**
     * Initialization
     */
    function init() {
        self
            .register('performance', ['ul', performance])
            .register('ul', ['li', ul])
            .register('li', li);
    }

    /**
     * Create jquery paginator performance
     *
     * @param {jQuery} wrapper
     * @returns {jQuery}
     * @private
     */
    function performance(wrapper) {
        return $('<nav class="zz-ui-paginator" />').append(wrapper);
    }

    /**
     * Create wrapper of items
     *
     * @param {jQuery} item
     * @returns {jQuery}
     * @private
     */
    function ul(item) {
        return $('<ul class="wrapper" />').append(item);
    }

    /**
     * Create paginator selector
     *
     * @returns {jQuery}
     * @private
     */
    function li() {
        return $('<li class="page" />')
            .attr('ng-repeat', 'page in UI.pages')
            .attr('ng-class', '{"current": page.isCurrent()}')
            .attr('ng-click', 'UI.currentPage(page.number)')
            .attr('pos', '{{ page.number }}')
            .append(
                $('<a class="zz-ui-button zz-fx" />').html('{{ page.number }}')
            );
    }
}