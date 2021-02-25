(function ($) {

    var abs = Math.abs,
        max = Math.max,
        min = Math.min,
        round = Math.floor;


    function div() {
        return $('<div/>');
    }


    $.imgAreaSelect = function (img, options) {
        var

            $img = $(img),

            imgLoaded,

            $box = div(),

            $area = div(),

            $border = div().add(div()).add(div()).add(div()),

            $outer = div().add(div()).add(div()).add(div()),

            $handles = $([]),

            $areaOpera,


            left, top,


            imgOfs,

            imgWidth, imgHeight,

            $parent,

            parOfs,

            zIndex = 0,

            position = 'absolute',

            startX, startY,

            scaleX, scaleY,

            resizeMargin = 10,

            resize,

            minWidth, minHeight, maxWidth, maxHeight,

            aspectRatio,

            shown,


            x1, y1, x2, y2,

            selection = {x1: 0, y1: 0, x2: 0, y2: 0, width: 0, height: 0},


            docElem = document.documentElement,


            $p, d, i, o, w, h, adjusted;


        function viewX(x) {
            return x + imgOfs.left - parOfs.left;
        }


        function viewY(y) {
            return y + imgOfs.top - parOfs.top;
        }


        function selX(x) {
            return x - imgOfs.left + parOfs.left;
        }

        function selY(y) {
            return y - imgOfs.top + parOfs.top;
        }


        function evX(event) {
            return event.pageX - parOfs.left;
        }


        function evY(event) {
            return event.pageY - parOfs.top;
        }

        function getSelection(noScale) {


            var sx = noScale || scaleX, sy = noScale || scaleY;

            return {
                x1: round(selection.x1 * sx),
                y1: round(selection.y1 * sy),
                x2: round(selection.x2 * sx),
                y2: round(selection.y2 * sy),
                width: round(selection.x2 * sx) - round(selection.x1 * sx),
                height: round(selection.y2 * sy) - round(selection.y1 * sy)
            };
        }


        function setSelection(x1, y1, x2, y2, noScale) {


            var sx = noScale || scaleX, sy = noScale || scaleY;

            selection = {
                x1: round(x1 / sx),
                y1: round(y1 / sy),
                x2: round(x2 / sx),
                y2: round(y2 / sy)
            };

            selection.width = selection.x2 - selection.x1;
            selection.height = selection.y2 - selection.y1;

        }

        function adjust() {

            if (!$img.width())
                return;

            imgOfs = {left: round($img.offset().left), top: round($img.offset().top)};

            imgWidth = $img.width();
            imgHeight = $img.height();

            /* Set minimum and maximum selection area dimensions */
            minWidth = options.minWidth || 0;
            minHeight = options.minHeight || 0;
            maxWidth = min(options.maxWidth || 1 << 24, imgWidth);
            maxHeight = min(options.maxHeight || 1 << 24, imgHeight);

            if ($().jquery == '1.3.2' && position == 'fixed' && !docElem['getBoundingClientRect']) {
                imgOfs.top += max(document.body.scrollTop, docElem.scrollTop);
                imgOfs.left += max(document.body.scrollLeft, docElem.scrollLeft);
            }

            /* Determine parent element offset */
            parOfs = $.inArray($parent.css('position'), ['absolute', 'relative']) + 1 ?
            {
                left: round($parent.offset().left) - $parent.scrollLeft(),
                top: round($parent.offset().top) - $parent.scrollTop()
            } :
                position == 'fixed' ?
                {left: $(document).scrollLeft(), top: $(document).scrollTop()} :
                {left: 0, top: 0};

            left = viewX(0);
            top = viewY(0);

            /*
             * Check if selection area is within image boundaries, adjust if
             * necessary
             */
            if (selection.x2 > imgWidth || selection.y2 > imgHeight)
                doResize();
        }

        function update(resetKeyPress) {

            if (!shown) return;

            $box.css({left: viewX(selection.x1), top: viewY(selection.y1)})
                .add($area).width(w = selection.width).height(h = selection.height);


            $area.add($border).add($handles).css({left: 0, top: 0});

            /* Set border dimensions */
            $border
                .width(max(w - $border.outerWidth() + $border.innerWidth(), 0))
                .height(max(h - $border.outerHeight() + $border.innerHeight(), 0));

            /* Arrange the outer area elements */
            $($outer[0]).css({
                left: left, top: top,
                width: selection.x1, height: imgHeight
            });
            $($outer[1]).css({
                left: left + selection.x1, top: top,
                width: w, height: selection.y1
            });
            $($outer[2]).css({
                left: left + selection.x2, top: top,
                width: imgWidth - selection.x2, height: imgHeight
            });
            $($outer[3]).css({
                left: left + selection.x1, top: top + selection.y2,
                width: w, height: imgHeight - selection.y2
            });

            w -= $handles.outerWidth();
            h -= $handles.outerHeight();

            /* Arrange handles */
            switch ($handles.length) {
                case 8:
                    $($handles[4]).css({left: w / 2});
                    $($handles[5]).css({left: w, top: h / 2});
                    $($handles[6]).css({left: w / 2, top: h});
                    $($handles[7]).css({top: h / 2});
                case 4:
                    $handles.slice(1, 3).css({left: w});
                    $handles.slice(2, 4).css({top: h});
            }

            if (resetKeyPress !== false) {
                /*
                 * Need to reset the document keypress event handler -- unbind the
                 * current handler
                 */
                if ($.imgAreaSelect.keyPress != docKeyPress)
                    $(document).unbind($.imgAreaSelect.keyPress,
                        $.imgAreaSelect.onKeyPress);

                if (options.keys)
                /*
                 * Set the document keypress event handler to this instance's
                 * docKeyPress() function
                 */
                    $(document)[$.imgAreaSelect.keyPress](
                        $.imgAreaSelect.onKeyPress = docKeyPress);
            }


            if ($.browser.msie && $border.outerWidth() - $border.innerWidth() == 2) {
                $border.css('margin', 0);
                setTimeout(function () {
                    $border.css('margin', 'auto');
                }, 0);
            }
        }

        function doUpdate(resetKeyPress) {


            adjust();
            update(resetKeyPress);
            x1 = viewX(selection.x1);
            y1 = viewY(selection.y1);
            x2 = viewX(selection.x2);
            y2 = viewY(selection.y2);
        }

        function hide($elem, fn) {
            options.fadeSpeed ? $elem.fadeOut(options.fadeSpeed, fn) : $elem.hide();
        }

        function areaMouseMove(event) {


            var x = selX(evX(event)) - selection.x1,
                y = selY(evY(event)) - selection.y1;

            if (!adjusted) {
                adjust();
                adjusted = true;

                $box.one('mouseout', function () {
                    adjusted = false;
                });
            }

            /* Clear the resize mode */
            resize = '';

            if (options.resizable) {

                if (y <= resizeMargin)
                    resize = 'n';
                else if (y >= selection.height - resizeMargin)
                    resize = 's';
                if (x <= resizeMargin)
                    resize += 'w';
                else if (x >= selection.width - resizeMargin)
                    resize += 'e';
            }

            $box.css('cursor', resize ? resize + '-resize' :
                options.movable ? 'move' : '');
            if ($areaOpera)
                $areaOpera.toggle();
        }


        function docMouseUp(event) {

            $('body').css('cursor', '');


            if (options.autoHide || selection.width * selection.height == 0)
                hide($box.add($outer), function () {
                    $(this).hide();
                });

            options.onSelectEnd(img, getSelection());

            $(document).unbind('mousemove', selectingMouseMove);
            $box.mousemove(areaMouseMove);
        }

        function areaMouseDown(event) {

            if (event.which != 1) return false;

            adjust();

            if (resize) {
                /* Resize mode is in effect */
                $('body').css('cursor', resize + '-resize');

                x1 = viewX(selection[/w/.test(resize) ? 'x2' : 'x1']);
                y1 = viewY(selection[/n/.test(resize) ? 'y2' : 'y1']);

                $(document).mousemove(selectingMouseMove)
                    .one('mouseup', docMouseUp);
                $box.unbind('mousemove', areaMouseMove);
            }
            else if (options.movable) {
                startX = left + selection.x1 - evX(event);
                startY = top + selection.y1 - evY(event);

                $box.unbind('mousemove', areaMouseMove);

                $(document).mousemove(movingMouseMove)
                    .one('mouseup', function () {
                        options.onSelectEnd(img, getSelection());

                        $(document).unbind('mousemove', movingMouseMove);
                        $box.mousemove(areaMouseMove);
                    });
            }
            else
                $img.mousedown(event);

            return false;
        }


        function fixAspectRatio(xFirst) {

            if (aspectRatio)
                if (xFirst) {
                    x2 = max(left, min(left + imgWidth,
                        x1 + abs(y2 - y1) * aspectRatio * (x2 > x1 || -1)));
                    y2 = round(max(top, min(top + imgHeight,
                        y1 + abs(x2 - x1) / aspectRatio * (y2 > y1 || -1))));
                    x2 = round(x2);
                }
                else {
                    y2 = max(top, min(top + imgHeight,
                        y1 + abs(x2 - x1) / aspectRatio * (y2 > y1 || -1)));
                    x2 = round(max(left, min(left + imgWidth,
                        x1 + abs(y2 - y1) * aspectRatio * (x2 > x1 || -1))));
                    y2 = round(y2);
                }
        }


        function doResize() {

            x1 = min(x1, left + imgWidth);
            y1 = min(y1, top + imgHeight);

            if (abs(x2 - x1) < minWidth) {
                /* Selection width is smaller than minWidth */
                x2 = x1 - minWidth * (x2 < x1 || -1);

                if (x2 < left)
                    x1 = left + minWidth;
                else if (x2 > left + imgWidth)
                    x1 = left + imgWidth - minWidth;
            }

            if (abs(y2 - y1) < minHeight) {
                /* Selection height is smaller than minHeight */
                y2 = y1 - minHeight * (y2 < y1 || -1);

                if (y2 < top)
                    y1 = top + minHeight;
                else if (y2 > top + imgHeight)
                    y1 = top + imgHeight - minHeight;
            }

            x2 = max(left, min(x2, left + imgWidth));
            y2 = max(top, min(y2, top + imgHeight));

            fixAspectRatio(abs(x2 - x1) < abs(y2 - y1) * aspectRatio);

            if (abs(x2 - x1) > maxWidth) {
                /* Selection width is greater than maxWidth */
                x2 = x1 - maxWidth * (x2 < x1 || -1);
                fixAspectRatio();
            }

            if (abs(y2 - y1) > maxHeight) {
                /* Selection height is greater than maxHeight */
                y2 = y1 - maxHeight * (y2 < y1 || -1);
                fixAspectRatio(true);
            }

            selection = {
                x1: selX(min(x1, x2)), x2: selX(max(x1, x2)),
                y1: selY(min(y1, y2)), y2: selY(max(y1, y2)),
                width: abs(x2 - x1), height: abs(y2 - y1)
            };


            update();

            options.onSelectChange(img, getSelection());
        }


        function selectingMouseMove(event) {

            x2 = resize == '' || /w|e/.test(resize) || aspectRatio ? evX(event) : viewX(selection.x2);
            y2 = resize == '' || /n|s/.test(resize) || aspectRatio ? evY(event) : viewY(selection.y2);

            doResize();

            return false;
        }


        function doMove(newX1, newY1) {

            x2 = (x1 = newX1) + selection.width;
            y2 = (y1 = newY1) + selection.height;

            $.extend(selection, {
                x1: selX(x1), y1: selY(y1), x2: selX(x2),
                y2: selY(y2)
            });

            update();

            options.onSelectChange(img, getSelection());
        }


        function movingMouseMove(event) {

            x1 = max(left, min(startX + evX(event), left + imgWidth - selection.width));
            y1 = max(top, min(startY + evY(event), top + imgHeight - selection.height));

            doMove(x1, y1);

            event.preventDefault();
            return false;
        }


        function startSelection() {

            adjust();

            x2 = x1;
            y2 = y1;
            doResize();

            resize = '';

            if ($outer.is(':not(:visible)'))
            /* Show the plugin elements */
                $box.add($outer).hide().fadeIn(options.fadeSpeed || 0);

            shown = true;

            $(document).unbind('mouseup', cancelSelection)
                .mousemove(selectingMouseMove).one('mouseup', docMouseUp);
            $box.unbind('mousemove', areaMouseMove);

            options.onSelectStart(img, getSelection());
        }


        function cancelSelection() {

            $(document).unbind('mousemove', startSelection);
            hide($box.add($outer));

            selection = {
                x1: selX(x1), y1: selY(y1), x2: selX(x1), y2: selY(y1),
                width: 0, height: 0
            };

            options.onSelectChange(img, getSelection());
            options.onSelectEnd(img, getSelection());
        }

        function imgMouseDown(event) {

            /* Ignore the event if animation is in progress */
            if (event.which != 1 || $outer.is(':animated')) return false;

            adjust();
            startX = x1 = evX(event);
            startY = y1 = evY(event);

            /* Selection will start when the mouse is moved */
            $(document).one('mousemove', startSelection)
                .one('mouseup', cancelSelection);

            return false;
        }

        function windowResize() {
            doUpdate(false);
        }

        function imgLoad() {

            imgLoaded = true;

            setOptions(options = $.extend({
                classPrefix: 'imgareaselect',
                movable: true,
                resizable: true,
                parent: 'body',
                onInit: function () {
                    if (options.upload == 1) {
                        selection = {
                            x1: options.x,
                            y1: options.y,
                            x2: (options.x + options.w),
                            y2: (options.y + options.h),
                            width: options.w,
                            height: options.h
                        };
                        shown = true;
                        adjust();
                        update();
                        $box.add($outer).hide().fadeIn(options.fadeSpeed || 0);
                    }
                },
                onSelectStart: function () {
                },
                onSelectChange: function () {
                },
                onSelectEnd: function () {
                }
            }, options));

            $box.add($outer).css({visibility: ''});

            if (options.show) {
                shown = true;
                adjust();
                update();
                $box.add($outer).hide().fadeIn(options.fadeSpeed || 0);
            }


            setTimeout(function () {
                options.onInit(img, getSelection());
            }, 0);
        }

        var docKeyPress = function (event) {

            var k = options.keys, d, t, key = event.keyCode;

            d = !isNaN(k.alt) && (event.altKey || event.originalEvent.altKey) ? k.alt :
                !isNaN(k.ctrl) && event.ctrlKey ? k.ctrl :
                    !isNaN(k.shift) && event.shiftKey ? k.shift :
                        !isNaN(k.arrows) ? k.arrows : 10;

            if (k.arrows == 'resize' || (k.shift == 'resize' && event.shiftKey) ||
                (k.ctrl == 'resize' && event.ctrlKey) ||
                (k.alt == 'resize' && (event.altKey || event.originalEvent.altKey))) {


                switch (key) {
                    case 37:
                        /* Left */
                        d = -d;
                    case 39:
                        /* Right */
                        t = max(x1, x2);
                        x1 = min(x1, x2);
                        x2 = max(t + d, x1);
                        fixAspectRatio();
                        break;
                    case 38:
                        /* Up */
                        d = -d;
                    case 40:
                        /* Down */
                        t = max(y1, y2);
                        y1 = min(y1, y2);
                        y2 = max(t + d, y1);
                        fixAspectRatio(true);
                        break;
                    default:
                        return;
                }

                doResize();
            }
            else {
                /* Move selection */

                x1 = min(x1, x2);
                y1 = min(y1, y2);

                switch (key) {
                    case 37:
                        /* Left */
                        doMove(max(x1 - d, left), y1);
                        break;
                    case 38:
                        /* Up */
                        doMove(x1, max(y1 - d, top));
                        break;
                    case 39:
                        /* Right */
                        doMove(x1 + min(d, imgWidth - selX(x2)), y1);
                        break;
                    case 40:
                        /* Down */
                        doMove(x1, y1 + min(d, imgHeight - selY(y2)));
                        break;
                    default:
                        return;
                }
            }

            return false;
        };

        function styleOptions($elem, props) {
            for (option in props)
                if (options[option] !== undefined)
                    $elem.css(props[option], options[option]);
        }

        function setOptions(newOptions) {

            if (newOptions.parent)
                ($parent = $(newOptions.parent)).append($box.add($outer));


            $.extend(options, newOptions);

            adjust();

            if (newOptions.handles != null) {

                $handles.remove();
                $handles = $([]);

                i = newOptions.handles ? newOptions.handles == 'corners' ? 4 : 8 : 0;

                while (i--)
                    $handles = $handles.add(div());


                $handles.addClass(options.classPrefix + '-handle').css({
                    position: 'absolute',

                    fontSize: 0,
                    zIndex: zIndex + 1 || 1
                });


                if (!parseInt($handles.css('width')) >= 0)
                    $handles.width(5).height(5);


                if (o = options.borderWidth)
                    $handles.css({borderWidth: o, borderStyle: 'solid'});


                styleOptions($handles, {
                    borderColor1: 'border-color',
                    borderColor2: 'background-color',
                    borderOpacity: 'opacity'
                });
            }

            scaleX = options.imageWidth / imgWidth || 1;
            scaleY = options.imageHeight / imgHeight || 1;

            if (newOptions.x1 != null) {
                setSelection(newOptions.x1, newOptions.y1, newOptions.x2,
                    newOptions.y2);
                newOptions.show = !newOptions.hide;
            }

            if (newOptions.keys)
                options.keys = $.extend({shift: 1, ctrl: 'resize'},
                    newOptions.keys);

            $outer.addClass(options.classPrefix + '-outer');
            $area.addClass(options.classPrefix + '-selection');
            for (i = 0; i++ < 4;)
                $($border[i - 1]).addClass(options.classPrefix + '-border' + i);

            styleOptions($area, {
                selectionColor: 'background-color',
                selectionOpacity: 'opacity'
            });
            styleOptions($border, {
                borderOpacity: 'opacity',
                borderWidth: 'border-width'
            });
            styleOptions($outer, {
                outerColor: 'background-color',
                outerOpacity: 'opacity'
            });
            if (o = options.borderColor1)
                $($border[0]).css({borderStyle: 'solid', borderColor: o});
            if (o = options.borderColor2)
                $($border[1]).css({borderStyle: 'dashed', borderColor: o});

            $box.append($area.add($border).add($handles).add($areaOpera));

            if ($.browser.msie) {
                if (o = $outer.css('filter').match(/opacity=([0-9]+)/))
                    $outer.css('opacity', o[1] / 100);
                if (o = $border.css('filter').match(/opacity=([0-9]+)/))
                    $border.css('opacity', o[1] / 100);
            }

            if (newOptions.hide)
                hide($box.add($outer));
            else if (newOptions.show && imgLoaded) {
                shown = true;
                $box.add($outer).fadeIn(options.fadeSpeed || 0);
                doUpdate();
            }

            aspectRatio = (d = (options.aspectRatio || '').split(/:/))[0] / d[1];

            $img.add($outer).unbind('mousedown', imgMouseDown);

            if (options.disable || options.enable === false) {

                $box.unbind('mousemove', areaMouseMove).unbind('mousedown', areaMouseDown);
                $(window).unbind('resize', windowResize);
            }
            else {
                if (options.enable || options.disable === false) {

                    if (options.resizable || options.movable)
                        $box.mousemove(areaMouseMove).mousedown(areaMouseDown);

                    $(window).resize(windowResize);
                }

                if (!options.persistent)
                    $img.add($outer).mousedown(imgMouseDown);
            }

            options.enable = options.disable = undefined;
        }


        this.remove = function () {
            $img.unbind('mousedown', imgMouseDown);
            $box.add($outer).remove();
        };

        this.getOptions = function () {
            return options;
        };


        this.setOptions = setOptions;

        this.getSelection = getSelection;

        this.setSelection = setSelection;

        this.update = doUpdate;

        $p = $img;

        while ($p.length) {
            zIndex = max(zIndex,
                !isNaN($p.css('z-index')) ? $p.css('z-index') : zIndex);

            if ($p.css('position') == 'fixed')
                position = 'fixed';

            $p = $p.parent(':not(body)');
        }


        zIndex = options.zIndex || zIndex;

        if ($.browser.msie)
            $img.attr('unselectable', 'on');

        /*
         * In MSIE and WebKit, we need to use the keydown event instead of keypress
         */
        $.imgAreaSelect.keyPress = $.browser.msie ||
        $.browser.safari ? 'keydown' : 'keypress';

        if ($.browser.opera)
            $areaOpera = div().css({
                width: '100%', height: '100%',
                position: 'absolute', zIndex: zIndex + 2 || 2
            });

        $box.add($outer).css({
            visibility: 'hidden', position: position,
            overflow: 'hidden', zIndex: zIndex || '0'
        });
        $box.css({zIndex: zIndex + 2 || 2});
        $area.add($border).css({position: 'absolute', fontSize: 0});


        img.complete || img.readyState == 'complete' || !$img.is('img') ?
            imgLoad() : $img.one('load', imgLoad);
    };


    $.fn.imgAreaSelect = function (options) {

        options = options || {};

        this.each(function () {

            if ($(this).data('imgAreaSelect')) {
                if (options.remove) {

                    $(this).data('imgAreaSelect').remove();
                    $(this).removeData('imgAreaSelect');
                }
                else

                    $(this).data('imgAreaSelect').setOptions(options);
            }
            else if (!options.remove) {

                if (options.enable === undefined && options.disable === undefined)
                    options.enable = true;

                $(this).data('imgAreaSelect', new $.imgAreaSelect(this, options));
            }
        });

        if (options.instance)

            return $(this).data('imgAreaSelect');

        return this;
    };

})(jQuery);


function photo(img, selection) {

    if ((selection.height > 0) && (selection.width > 0)) {
        var scaleX = 75 / selection.width;
        var scaleY = 100 / selection.height;

        $('#preview img, #preview_small img').attr('src', $('#photo_1').attr('src'));
        $('#preview img').css({
            width: Math.round(scaleX * $('#photo_1').width()),
            height: Math.round(scaleY * $('#photo_1').height()),
            marginLeft: -Math.round(scaleX * selection.x1),
            marginTop: -Math.round(scaleY * selection.y1)
        });

        var scaleX = 45 / selection.width;
        var scaleY = 60 / selection.height;

        $('#preview_small img').css({
            width: Math.round(scaleX * $('#photo_1').width()),
            height: Math.round(scaleY * $('#photo_1').height()),
            marginLeft: -Math.round(scaleX * selection.x1),
            marginTop: -Math.round(scaleY * selection.y1)
        });


        window.x = selection.x1;
        window.y = selection.y1;
        window.h = selection.height;
        window.w = selection.width;

        $('#crop_image_1').removeAttr('disabled');
    }
    else
        $('#crop_image_1').attr('disabled', 'disabled');

}

$(function () {
    $('#crop_image_1').attr('disabled', 'disabled');
    $('#crop_image_1').click(function () {
        if ((h > 0) && ($('input[name="id"]').val()))
            $.ajax({
                type: 'post',
                url: '/profile/crop_photo',
                data: ({
                    id: $('input[name="id"]').val(),
                    xcor: x,
                    ycor: y,
                    height: h,
                    width: w,
                    img_w: $('#photo_1').width(),
                    img_h: $('#photo_1').height()
                }),
                beforeSend: function () {
                    $('#photo_crop_wait').show();
                },
                success: function () {
                    $('#preview_errors').fadeIn(250, function () {
                        $('#preview_errors').fadeIn(250).fadeOut(3000);
                    });
                    $('#photo_crop_wait').hide();
                    window.location = '/profile/edit?id=' + $('input[name="id"]').val() + '&tab=photo';
                }
            });
        else {
            $('#preview_errors').fadeIn(250, function () {
                $('#preview_errors').html('Спочатку виділіть область').fadeOut(3000);
            });
        }

    });

});

function clearForm() {
    $('input[type="text"]').not(':disabled').val('');
}
function validateReceiveHelpParams() {
    $err = '';
    if ($('input[name="user_to"]').val() == '')
        $err += 'Кому';
    if ($('textarea[name="describe"]').val() == '')
        $err += 'Деталізація';
    if ($('input[name="recive_help_date"]').val() == '')
        $err += 'Дата';
    return $err;
}

$(function () {

    $('#already_receive').click(function () {
//        alert('!');
        if ($('#received_help_form').css('display') == 'none')
            $('#received_help_form').show();
        else
            $('#received_help_form').hide();
    });

    $('.upd_btn').click(function () {
        $upd = $(this).attr('update_id');
        $table_id = '#table_' + $upd;
        $($table_id).find('input').not(':button').removeAttr('disabled');
        $($table_id).find('textarea').removeAttr('disabled');
        $($table_id).find('select').removeAttr('disabled');
        $($table_id).find('.change_help_btn').show();

    });
    $('.del_btn').click(function () {
        $del = $(this).attr('delete_id');
        $.ajax({
            type: 'post',
            url: '/profile/desktop_edit',
            data: ({
                update: '0',
                submit: 'true',
                type: "change_help",
                key: $del
            }),
            success: function () {
                $table_id = '#table_' + $del;
                $('table').remove($table_id);

            }
        });

    });
    $('.change_help_btn').click(function () {
        $rec = $(this).attr('rec_id');
        if ($(this).attr('need') == 1)
            $desc = $('textarea[name="change_help_review_' + $rec + '"]').val();
        else
            $desc = $('textarea[name="change_help_describe_' + $rec + '"]').val();
        $.ajax({
            type: 'post',
            url: '/profile/desktop_edit',
            data: ({
                update: '1',
                need: $(this).attr('need'),
                key: $rec,
                type: 'change_help',
                submit: 'true',
                help_describe: $desc,
                help_type: $('select[name="change_help_type_' + $rec + '"] :selected').val(),
                hours_per_week: $('input[name="change_hours_per_week_' + $rec + '"]').val()

            }),
            success: function () {
                $table_id = '#table_' + $rec;
                $($table_id).find('input').not(':button').attr('disabled', 'disabled');
                $($table_id).find('textarea').attr('disabled', 'disabled');
                $($table_id).find('select').attr('disabled', 'disabled');
                $($table_id).find('.change_help_btn').hide();

            }
        });
    });


    $('#add_help_btn, #add_help_btn1').click(function () {
        $err = false;
        if ($(this).attr('need') == 1) {
            if ($('textarea[name="help_review"]').val() == '') {
                $err = true;
            }
        }
        else if ($(this).attr('need') == 0)
            if ($('textarea[name="help_describe"]').val() == '') {
                $err = true;
            }
        if ($('select[sel_need="' + $(this).attr('need') + '"] option:selected').val == -1)
            if ($('input[inp_need="' + $(this).attr('need') + '"]').val() == '')
                $err = true;
        if ($err == false)
            $.ajax({
                type: 'post',
                url: '/profile/desktop_edit',
                data: ({
                    need: $(this).attr('need'),
                    help_review: $('textarea[name="help_review"]').val(),
                    type: 'help',
                    submit: 'true',
                    help_describe: $('textarea[name="help_describe"]').val(),
                    help_type: $('select[sel_need="' + $(this).attr('need') + '"] option:selected').val(),
                    new_type: $('input[inp_need="' + $(this).attr('need') + '"]').val(),
                    hours_per_week: $('input[name="hours_per_week"]').val(),

                }),
                success: function () {
                    $('.help_success').fadeIn(250, function () {
                        $('.help_success').html('Інформація успішно додана').fadeOut(1500);
                    });
                    clearForm('help_form');
                    window.location = window.location;

                }
            });
        else
            $('.help_success').fadeIn(250, function () {
                $('.help_success').html('Заповніть усі поля').fadeOut(3000);
            });
    });
})


//------------------------------------------HELP---------------------------------
$(function () {
    $('#received_help_btn').click(function () {
        $err = true;

        if ($('select[name="year_from"] option:selected').val() <= $('select[name="year_to"] option:selected').val()) {
            $err = false;
            //alert('yfrom<yto');
            if ($('select[name="year_from"] option:selected').val() == $('select[name="year_to"] option:selected').val())
                if ($('select[name="mounth_from"] option:selected').val() > $('select[name="mounth_to"] option:selected').val())
                    $err = true;


        }
        if (($('#search_users').attr('uid') < 0) || ($('textarea[name="describe"]').val() == '')) {
            $err = true;
        }

        if ($err == false) {
            $.ajax({
                type: 'post',
                url: '/profile/desktop_edit',
                data: ({
                    user_to: $('#search_users').attr('uid'),
                    mounth_from: $('select[name="mounth_from"] option:selected').val(),
                    year_from: $('select[name="year_from"] option:selected').val(),
                    mounth_to: $('select[name="mounth_to"] option:selected').val(),
                    year_to: $('select[name="year_to"] option:selected').val(),
                    help_type: $('select[name="received_help_type"] option:selected').val(),
                    type: 'receive',
                    submit: 'true',
                    help_describe: $('textarea[name="describe"]').val()
                }),
                success: function () {
                    sendMessage();
                    $('.receive_success').fadeIn(250, function () {
                        $('.receive_success').fadeOut(1500);
                    });
                    clearForm('received_help_form');


                }
            });
        }
        else
            $('.receive_success').fadeIn(250, function () {
                $('.receive_success').html('Заповнені не всі поля або введено некоректну дату')
            });
    });
    $('input[name="active_btn"]').click(function () {
        $this = $(this);
        $stat = $this.attr('active');
        $.ajax({
            type: "post",
            url: '/profile/desktop_edit',
            data: ({
                act: $stat,
                submit: 'true',
                type: 'active_help'

            }),
            success: function () {
                $this.attr('active', ($stat == 1) ? '0' : '1');
                $this.attr('value', ($stat == 1) ? 'Тимчасово не надаю допомогу' : 'Готовий надавати допомогу');
            }
        })
    });

    $('input[type="button"]').each(function () {
        $(this).click(function () {
            // alert('app');
            if ($(this).attr('approve_id')) {

                $tmp = '#app_msg_' + $(this).attr('approve_id');
                $ident = $(this).attr('approve_id');

                $.ajax({
                    type: 'post',
                    url: '/ajax/user_search',
                    data: ({
                        approved: '1',
                        help_id: $(this).attr('approve_id')
                    }),
                    success: function () {
                        $($tmp).fadeIn(300, function () {
                            $(this).html('Інформація підтвердженна');
                            $(this).fadeOut(3000);
                        });
                        //$($tmp).html('Інформація підтвердженна');
//                           $(thiss).remove();
                        $('input').each(function () {
                            if ($ident == $(this).attr('refuse_id') || $ident == $(this).attr('approve_id'))
                                $(this).remove();
                        });
                    }
                });

            }

            if ($(this).attr('refuse_id')) {
                $tmp = '#app_msg_' + $(this).attr('refuse_id');
                $ident = $(this).attr('refuse_id');
                $.ajax({
                    type: 'post',
                    url: '/ajax/user_search',
                    data: ({
                        approved: '2',
                        help_id: $(this).attr('refuse_id')
                    }),
                    success: function () {
                        //$tmp = '#app_msg_'+$(this).attr('refuse_id');
                        //alert($tmp);
                        $($tmp).fadeIn(300, function () {
                            $(this).html('Інформація спростована');
                            $(this).fadeOut(3000);
                        });
                        // $($tmp).html('Інформація спростована');$(this).remove();
                        $('input').each(function () {
                            if ($ident == $(this).attr('approve_id') || $(this).attr('refuse_id') == $ident)
                                $(this).remove();
                        });
                    }
                });

            }

            if ($(this).attr('review_add_id')) {
                $tmp = '#help_review_' + $(this).attr('review_add_id');
                $label = '#review_success_' + $(this).attr('review_add_id');
                $ident = $(this).attr('review_add_id');
                $.ajax({
                    type: 'post',
                    url: '/ajax/user_search',
                    data: ({
                        review: $($tmp).val(),
                        help_id: $(this).attr('review_add_id')
                    }),
                    success: function () {
                        $($label).fadeIn(300, function () {
                            $(this).html('Коментар додано');
                            $(this).fadeOut(3000);
                        });
                        $(this).remove();

                        $('textarea').each(function () {
                            if ($(this).attr('id') == 'help_review_' + $ident)
                                $(this).remove();
                        });

                        $('input').each(function () {
                            if ($(this).attr('review_add_id') == $ident || $(this).attr('cancel_id') == $ident)
                                $(this).remove();
                        });
                    }
                });

            }
        });

    });
})
function sendMessage() {
    //alert('try send');
    $.ajax({
        type: 'post',
        url: '/messages/compose',
        data: ({
            body: $('#search_users').val() + ' вказав у своєму робочому столі, що надав Вам ' + $('select[name="received_help_type"] option:selected').html() + ' допомогу, а також зазначив наступне: ' + $('textarea[name="describe"]').val() + '.  Щоб підтвердити або спростувати дану інформацію, перейдіть за цим <a href="http://meritokrat.org/profile/desktop?id=' + $('#search_users').attr('uid') + '&tab=help#ancor">посиланням</a>. ',
            receiver: $('#search_users').val(),
            receiver_id: $('#search_users').attr('uid'),
            sender_id: '0',
            submit: 'true'
        }),
        success: function () {
            //alert('ok');
        }
    });
}

//

var profileController = new function () {
    this.desktop_editAction = function () {
        var functionForm = new Form(
            'function_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'function_wait'
            }
        );
        var helpForm = new Form(
            'help_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'function_wait'
            }
        );
        var informationForm = new Form(
            'information_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'information_wait'
            }
        );
        var paymentsConfForm = new Form(
            'payments_conf_form',
            {
                validators: {},
                success: function (responce) {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'payment_wait'
            }
        );
        var membershipForm = new Form(
            'membership_form',
            {
                validators: {},
                success: function (responce) {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'membership_wait'
            }
        );
        var peoplesForm = new Form(
            'peoples_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'peoples_wait'
            }
        );
        var meetingsForm = new Form(
            'meetings_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'meetings_wait'
            }
        );
        var meetingsForm = new Form(
            'events_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'events_wait'
            }
        );
        var meetingsForm = new Form(
            'educations_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'educations_wait'
            }
        );
        var meetingsForm = new Form(
            'tasks_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'tasks_wait'
            }
        );
        var otherForm = new Form(
            'other_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'other_wait'
            }
        );
        var avatarmForm = new Form(
            'avatarm_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'avatarm_wait'
            }
        );


        $('.tab_menu').click(function () {
            $('.tab_menu').removeClass('selected');
            $(this).addClass('selected');
            $(this).blur();
            $('.form').hide();

            $('#' + $(this).attr('rel') + '_form').show();

        });

        $('#tab_' + this.defaultTab).click();


    };


    this.editAction = function () {

        var commonForm = new Form(
            'common_form',
            {
                validators: {
                    first_name: [validatorRequired],
                    last_name: [validatorRequired],
                    country: [validatorRequired],
                    //region: [validatorRequired],
                    //city: [validatorRequired]
                },
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                    window.location = '/profile/edit?id=' + $('input[name="id"]').val();
                },
                wait_panel: 'common_wait'
            }
        );

        var contactsForm = new Form(
            'contacts_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'contacts_wait'
            }
        );

        var work_spaceForm = new Form(
            'work_space_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'work_space_wait'
            }
        );

        var educationForm = new Form(
            'education_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'education_wait'
            }
        );

        var workForm = new Form(
            'work_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'work_wait'
            }
        );

        var interestsForm = new Form(
            'interests_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'interests_wait'
            }
        );

        var bioForm = new Form(
            'bio_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'bio_wait'
            }
        );

        var settingsForm = new Form(
            'settings_form',
            {
                validators: {
                    'aemail': [validatorRequired, validatorEmail],
                    'new_password': function () {
                        if (settingsForm.get('new_password').val() != '') {
                            if (settingsForm.get('new_password_confirm').val() != settingsForm.get('new_password').val()) {
                                return true;
                            }
                        }

                        return false;
                    }
                },
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'settings_wait'
            }
        );

        var photoForm = new Form(
            'photo_form',
            {
                success: function (uri) {
                    //alert(uri.length);
                    $('#photo_load_errors').fadeIn(250, function () {
                        $('#photo_load_errors').fadeOut(1500);
                    });
                    //$('#photo_1').attr('src', uri);

                    window.location = '/profile/edit?id=' + $('input[name="id"]').val() + '&tab=photo';

                },
                wait_panel: 'photo_wait'
            }
        );

        var admin_infoForm = new Form(
            'admin_info_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'admin_info_wait'
            }
        );

        var contact_infoForm = new Form(
            'contact_info_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'contact_info_wait'
            }
        );

        var admin_contactForm = new Form(
            'admin_contact_form',
            {
                validators: {},
                success: function () {
                    $('.success').fadeIn(250, function () {
                        $('.success').fadeOut(1500);
                    });
                },
                wait_panel: 'admin_contact_wait'
            }
        );

        var politicalForm = new Form(
            'political_form',
            {
                validators: {},
                success: function (responce) {
                    if (!responce.error) {
                        $('.success').fadeIn(250, function () {
                            $('.success').fadeOut(1500);
                        });
                    } else {
                        $('.error').fadeIn(250, function () {
                            $('.error').fadeOut(2500);
                        });
                    }
                },
                wait_panel: 'political_wait'
            }
        );

        $('.tab_menu').click(function () {
            $('.tab_menu').removeClass('selected');
            $(this).addClass('selected');
            $(this).blur();
            $('.form').hide();
            $('#' + $(this).attr('rel') + '_form').show();
            if ($(this).attr('rel') != 'photo') {
                $('#ui-datepicker-div').next().css('display', 'none');
                $('body').find('.imgareaselect-outer').each(function () {
                    $(this).css('display', 'none');
                });
            }
            else {
                window.setTimeout(loadImgarea, 1000);
            }

        });

        $('#tab_' + this.defaultTab).click();

        commonForm.get('city').autocomplete({
            cache: false, minchars: 2, noresults: 'Невідоме місто', ajax_get: function (key, cont) {
                $.post(
                    '/profile/get_city',
                    {key: key},
                    function (r) {
                        var res = [];
                        for (var i = 0; i < r.length; i++) res.push({
                            id: r[i].id,
                            value: r[i]['name_' + context.language],
                            info: r[i]['region_name_' + context.language]
                        });
                        cont(res);
                    },
                    'json');
            }, callback: function (data) {
                commonForm.get('city_id').val(data.id);
            }
        });


        commonForm.get('region').autocomplete({
            cache: false, minchars: 2, noresults: 'Невідомий регіон', ajax_get: function (key, cont) {
                $.post(
                    '/profile/get_region',
                    {key: key},
                    function (r) {
                        var res = [];
                        for (var i = 0; i < r.length; i++) res.push({
                            id: r[i].id,
                            value: r[i]['name_' + context.language],
                            info: r[i]['country_name_' + context.language]
                        });
                        cont(res);
                    },
                    'json');
            }, callback: function (data) {
                commonForm.get('country_id').val(data.id);
            }
        });


        commonForm.get('country').autocomplete({
            cache: false, minchars: 2, noresults: 'Невідома країна', ajax_get: function (key, cont) {
                $.post(
                    '/profile/get_country',
                    {key: key},
                    function (r) {
                        var res = [];
                        for (var i = 0; i < r.length; i++) res.push({
                            id: r[i].id,
                            value: r[i]['name_' + context.language]
                        });
                        cont(res);
                    },
                    'json');
            }, callback: function (data) {
                commonForm.get('country_id').val(data.id);
                if (data.id == '1') {
                    document.getElementById('region_row').style.display = 'table-row';
                }
            }
        });

    };


    this.reSend = function (id) {
        $.get('/admin/user_resend', {id: id}, function (data) {
            if (data != 'ok') {
                Popup.show();
                Popup.setHtml(data);
                Popup.position();
            } else {
                $('#resend' + id).hide();
                $('#status' + id).html(data);
            }
        });

    }

    this.trust = function (id, trust) {
        $('#trust_wait').show();
        $.post(
            '/profile/trust',
            {id: id, trust: trust ? 1 : 0},
            function () {
                $('#trust_wait').hide();

                $('.custom_rate_selected').removeClass('custom_rate_selected');
                $('#' + ( trust ? 'trust' : 'not_trust' )).addClass('custom_rate_selected');

                if (trust) {
                    $('#trust_value').html(parseInt($('#trust_value').html()) + 1);
                }
                else {
                    $('#not_trust_value').html(parseInt($('#not_trust_value').html()) + 1);
                }
            },
            'json'
        );
    };

    this.indexAction = function () {
        $('.tab_pane > a').bind('click', function () {
            $('.tab_pane > a').removeClass('selected');
            $(this).addClass('selected');
            $('.content_pane').hide();
            $('#pane_' + $(this).attr('rel')).show();
            $(this).blur();
        });


        var askForm = new Form(
            'ask_form',
            {
                validators: {
                    text: [validatorRequired]
                },
                success: function (response) {
                    $('#no_question').hide();
                    $('#quesiton_success').fadeIn(150);
                    $('#ask_form').hide();
                },
                wait_panel: 'ask_wait'
            }
        );

    };

    this.desktopAction = function () {
        $('.tab_pane > a').bind('click', function () {
            $('.tab_pane > a').removeClass('selected');
            $(this).addClass('selected');
            $('.content_pane').hide();
            $('#pane_' + $(this).attr('rel')).show();
            $(this).blur();
        });

    };

    this.questionsAction = function () {
        var askForm = new Form(
            'ask_form',
            {
                validators: {
                    text: [validatorRequired]
                },
                success: function (response) {
                    $('#question_success').fadeIn(150);
                },
                wait_panel: 'ask_wait'
            }
        );

        var replyForm = new Form(
            'question_reply_form',
            {
                validators: {
                    reply: [validatorRequired]
                },
                success: function (response) {
                    $('#reply_' + replyForm.get('id').val()).append(response);
                    replyForm.getForm().hide();
                },
                format: 'raw',
                wait_panel: 'reply_wait'
            }
        );

        $('.question_reply').bind('click', function () {
            $('#question_reply_form').appendTo($(this).parent());
            $('#question_reply_form').show();
            replyForm.get('id').val($(this).attr('rel'));
        });
    };

    this.rateQuestion = function (object, id, positive) {
        $(object).parent().fadeOut(150);

        var rateEl = $(object).parent().parent().children('span.bold');
        var newRate = parseInt(rateEl.html()) + (positive ? 1 : -1);

        if (newRate > 0) {
            newRate = '+' + newRate;
            rateEl.css({color: 'green'});
        }
        else {
            rateEl.css({color: 'red'});
        }

        rateEl.html(newRate);

        $.post(
            '/profile/question_rate',
            {id: id, positive: positive ? 1 : 0}
        );
    };

    this.deleteProfile = function (id, type, force = false) {
        Popup.show();
        $.get('/profile/delete?id=' + id + '&type=' + type, function (response) {
            Popup.setHtml(response);
            Popup.position();
        });
    }

    this.hideProfile = function (id) {
        $.get('/profile/hide?id=' + id, function (response) {
            $("tr[data-id=" + id + "]").remove();
        });
    }

    this.unBan = function (id) {
        $('#banned_' + id).fadeOut(150);
        $.post('/friends/unban', {id: id});
    }
    this.deleteAvtophotoItem = function (id, user_id, salt) {
        $('#photo_' + id).fadeOut(150);
        $.post('/profile/delete_desktop_photo', {id: id, user_id: user_id, salt: salt});
    };
};
function min(a, b) {
    if (a <= b)
        return a;
    else
        return b;
}
$(function () {
    $('select[name="help_type"]').change(function () {
        $id = $(this).attr('sel_need');
        if ($('select[sel_need="' + $id + '"] option:selected').val() == '-1')
            $('input[inp_need="' + $id + '"]').show();
        else
            $('input[inp_need="' + $id + '"]').hide();
    });
});
function loadImgarea() {
    $W = document.getElementById('photo_1').width;
    $H = document.getElementById('photo_1').height;

    if ((crop_x != -1) || (crop_y != -1) || (crop_w != -1) || (crop_h != -1)) {
        window.x = crop_x;
        window.y = crop_y;
        window.w = crop_w;
        window.h = crop_h;
    }
    else {
        if ($W >= $H * 0.7777) {
            window.w = $H * 0.77777;
            window.h = $H;
        }
        else {
            window.w = $W;
            window.h = $W * 1.285;
        }
        window.x = ($W - window.w) / 2;
        window.y = ($H - window.h) / 2;
    }

    $('#photo_1').imgAreaSelect({
        aspectRatio: '3.5:4.5',
        handles: true,
        onSelectEnd: photo,
        upload: 1,
        x: window.x,
        y: window.y,
        h: window.h,
        w: window.w
    });

    $('body').find('.imgareaselect-outer').prev().css('display', 'block');

    $('body').find('.imgareaselect-outer').each(function () {
        $(this).css('display', 'block');
    });

}
function askRecommendation(uId, uStat) {
    Popup.show();
    $.post(
        '/help/send_message',
        {
            uid: uId,
            ustat: uStat
        },
        function (response) {
            Popup.setHtml(response);
            Popup.position();
        }
    );
}
