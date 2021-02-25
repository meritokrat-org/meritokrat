<script type="text/javascript">
    var context = {
        module: '<?=context::get_controller()->get_module() ?>',
        action: '<?=context::get_controller()->get_action() ?>',
        obj_id: '<?=request::get('id') ?>',
        static_server: '<?=context::get('static_server')?>',
        host: '<?=context::get('host')?>',
        user_id: <?=session::is_authenticated() ? session::get_user_id() : 'null'?>,
        user_fio: '<?=session::is_authenticated() ? addslashes(user_helper::full_name(session::get_user_id(), false)) : null?>',
        language: '<?=translate::get_lang()?>'
    };

    <?=client_helper::get_variables()?>
</script>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-20010529-1']);
    _gaq.push(['_trackPageview']);

    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();

</script>