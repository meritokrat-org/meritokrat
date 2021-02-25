<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.mouse.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.slider.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script>
<?if(translate::get_lang() == 'ru'){?>
    var PickerTime='Время';
    var PickerHour='Часы';
    var PickerMinute='Минуты';
<?}else{?>
    var PickerTime='Час';
    var PickerHour='Години';
    var PickerMinute='Час';
<?}?>
</script>
<script type="text/javascript" src="/static/javascript/jquery/jquery-ui-timepicker-addon-0.5.min.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-<?=translate::get_lang() == 'ru' ? 'ru' : 'uk'?>.js"></script>
<script type="text/javascript">jQuery.noConflict();</script>
<style>
#ui-datepicker-div{ background: #FFF; box-shadow: 0 0 10px rgba(0,0,0,.3) }
.ui-timepicker-div .ui-widget-header{ margin-bottom: 8px; }
.ui-timepicker-div dl{ text-align: left; }
.ui-timepicker-div dl dt{ height: 25px; }
.ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
.ui-timepicker-div .ui_tpicker_hour div { padding-right: 2px; }
.ui-timepicker-div .ui_tpicker_minute div { padding-right: 6px; }
.ui-timepicker-div .ui_tpicker_second div { padding-right: 6px; }
.ui-timepicker-div td { font-size: 90%; }
</style>