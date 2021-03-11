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