var Form = function (id, options) {
    var formObject = $('#' + id);
    var elements = {};

    if (typeof options == 'undefined') {
        options = {};
    }

    if (typeof options.error_position == 'undefined') {
        options.error_position = 'right';
    }

    if (typeof options.wait_panel == 'undefined') {
        options.wait_panel = 'wait_panel';
    }

    if (typeof options.success == 'undefined') {
        options.success = function () {
        };
    }

    if (typeof options.format == 'undefined') {
        options.format = 'json';
    }

    if (typeof options.handle == 'undefined') {
        options.handle = true;
    }

    if (!formObject.get(0)) {
        return;
    }

    for (i = 0; i < formObject.get(0).elements.length; i++) {
        var formElement = $(formObject.get(0).elements[i]);
        var name = formElement.attr('name').replace('[', '_').replace(']', '');

        if (formElement.attr('type') == 'radio') {
            formElement.attr('id', name + '_' + formElement.val());
        }
        // else {
        //     if (formElement.attr('id') == '')
        //         formElement.attr('id', name);
        // }
        
        elements[formElement.attr('name')] = formElement;
    }

    var isValid = function () {
        if (typeof options.preValidate == 'function') {
            if (!options.preValidate()) {
                return false;
            }
        }

        if (!options.validators) {
            return true;
        }

        for (var name in options.validators) {
            var value = elements[name].val();
            var validators = options.validators[name];

            if (typeof validators != 'object') {
                validators = [validators];
            }

            for (var v = 0; v < validators.length; v++) {
                var validator = validators[v];
                var errorCode = validator(value);

                if (errorCode) {
                    showError(name, getErrorByCode(name, errorCode));
                    return false;
                }
            }
        }

        return true;
    };

    var showError = function (name, error) {
        $('#' + name).floatingHint(error, {position: options.error_position});
        $('#' + name).focus();
    }

    var submit = function () {
        $('#floating_hint').hide();

        if (isValid()) {
            if (typeof options.preSubmit == 'function') {
                if (!options.preSubmit()) {
                    return false;
                }
            }

            $('#' + options.wait_panel).show();
            elements['submit'].attr('disabled', true);

            var action = formObject.attr('action');
            if (!action) {
                action = '/' + context.module + '/' + context.action;
            }

            if (formObject.attr('enctype') != 'multipart/form-data') {
                $.post(
                    action,
                    formObject.serialize() + '&submit=true',
                    handleResponse,
                    options.format
                );
            }
            else {
                $.ajaxFileUpload(
                    {
                        url: action,
                        secureuri: false,
                        fileElementId: 'file',
                        dataType: options.format,
                        success: function (data, status) {
                            handleResponse(data);
                        },
                        error: function (data, status, e) {
                            showError('file', e);
                        }
                    }
                );
            }
        }

        return false;
    };

    var handleResponse = function (response) {
        elements['submit'].attr('disabled', false);
        $('#' + options.wait_panel).hide();

        if (( options.format == 'json' ) && response.errors) {
            for (var name in response.errors) {
                for (var i = 0; i < response.errors[name].length; i++) {
                    showError(name, response.errors[name][i]);
                    return false;
                }
            }
        }

        options.success(response);
    }

    this.get = function (name) {
        return elements[name];
    };

    this.set = function (name, value) {

        elements[name] = value;
    };

    this.getForm = function () {
        return formObject;
    };

    this.getId = function () {
        return formObject.attr('id');
    };

    var getErrorByCode = function (name, code) {
        var errors = elements[name].attr('rel').split(';');

        for (var i = 0; i < errors.length; i++) {
            var error = errors[i].split(':');
            if (error[0] == code) {
                return error[1];
            }
        }

        return null;
    }

    if (options.handle) {
        formObject.bind('submit', submit);
    }
    else {
        formObject.bind('submit', isValid);
    }
}
