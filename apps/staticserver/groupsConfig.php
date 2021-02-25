<?php

$groups = array(
    'system_css' => array(
        '//css/reset.css',
        '//css/grid.css',
        '//css/typography.css',
        '//css/forms.css',
        '//css/custom.css',
        '//css/predefined.css',
        '//css/plugins/autocomplete.css',
        '//css/jquery/jquery.ui.core.css',
        '//css/jquery/jquery.ui.accordion.css',
        '//css/jquery/jquery.ui.autocomplete.css',
        '//css/jquery/jquery.ui.button.css',
        '//css/jquery/jquery.ui.datepicker.css',
        '//css/jquery/jquery.ui.dialog.css',
        '//css/jquery/jquery.ui.progressbar.css',
        '//css/jquery/jquery.ui.resizable.css',
        '//css/jquery/jquery.ui.selectable.css',
        '//css/jquery/jquery.ui.slider.css',
        '//css/jquery/jquery.ui.tabs.css',
        '//css/jquery/jquery.ui.theme.css',
    ),

    'system_js' => array(
        '//javascript/library/jquery.js',
        '//javascript/library/ui/core/ui.core.js',
        '//javascript/library/plugins/cookie.js',
        '//javascript/library/plugins/dimensions.js',
        '//javascript/library/plugins/calendar.js',
        '//javascript/library/plugins/floating.hint.js',
        '//javascript/library/plugins/inline.hint.js',
        '//javascript/library/plugins/autocomplete.js',
        '//javascript/library/plugins/ajax.file.upload.js',
        '//javascript/library/form/form.js',
        '//javascript/library/form/validators.js',
        '//javascript/library/application.js',
        '//javascript/library/components/hint.js',
        '//javascript/library/components/popup.js',
        /*		'//javascript/jquery/jquery-1.4.2.js',
            '//javascript/jquery/jquery.ui.core.js',
            '//javascript/jquery/selects.js',
                '//javascript/jquery/jquery.ui.widget.js',
                '//javascript/jquery/jquery.autocomplete.js',*/
    ),

    'tinymce_js' => array(
        '//javascript/library/tinymce/tiny_mce.js',
    ),

    'debug_js' => array(
        '//javascript/library/debug.js',
    ),

    'module_parties_js' => array(
        '//javascript/library/components/swfobject.js',
    ),

    'module_admin_js' => array(
        '//javascript/library/components/swfobject.js',
    ),

    'module_people_js' => array(
        '//javascript/library/components/swfobject.js',
    ),

    'module_m2010_js' => array(
        '//javascript/library/components/swfobject.js',
    ),

    'module_profile_js' => array(
        '//javascript/library/plugins/select.js',
        '//javascript/library/components/swfobject.js',
    ),

    'module_sign_js' => array(
        '//javascript/library/plugins/select.js',
    ),
);

$group = $_GET['g'];
if (strpos($group, 'module_') === 0) {
    if (strpos($group, '_js')) {
        $type = 'javascript';
        $ext  = 'js';
    } else {
        $type = 'css';
        $ext  = 'css';
    }

    $module = str_replace(array('module_', '_js', '_css'), '', $group);

    if ($module) {
        $groups[$group][] = "//{$type}/modules/{$module}.{$ext}";
    }
}

return $groups;