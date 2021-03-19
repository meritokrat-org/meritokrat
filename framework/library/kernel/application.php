<?php

load::system('render/html_render');

class application
{
    public function execute($app_name)
    {
        $this->init();

        load::system('http/request');
        load::system('http/cookie');
        load::system('http/session');

        if (conf::get('enable_log')) {
            $log_id = logger::start('Application executed');
        }

        load::action_helper('client');
        context::set('host', getenv('SERVER_NAME') ? getenv('SERVER_NAME') : $_SERVER['SERVER_NAME']);

        if (conf::get('server')) {
            context::set('server', conf::get('server'));
        } else {
            context::set(
                'server',
                'https://'.getenv('SERVER_NAME') ? getenv('SERVER_NAME') : $_SERVER['SERVER_NAME'].'/'
            );
        }

        context::set('static_server', 'https://s'.rand(1, conf::get('static_servers')).'.'.context::get('server').'/');
        context::set('image_server', 'https://image.'.context::get('server').'/');
        context::set('file_server', 'https://f.'.context::get('server').'/');
        $i18n_conf = conf::get('i18n');
        if ($i18n_conf['enabled']) {
            load::system('i18n/translate');
            translate::set_lang($i18n_conf['default_lang']);
        }

        context::set_app($app_name);
        load::app('controller');

        $module = $this->get_module();
        $action = $this->get_action();

        $module_path = 'modules/'.str_replace('_', '/modules/', $module).'/actions/'.$action.'.action';

        try {
            load::app($module_path);
        } catch (load_exception $e) {
            load::system($module_path);
        }

        ini_set('session.cookie_domain', '.'.context::get('host'));
        session::start();

        $action_class = $module.'_'.$action.'_action';
        $controller   = new $action_class($module, $action);
        context::set_controller($controller);

        try {
            $output = $controller->run();
        } catch (public_exception $e) {
            $error_module = conf::get('error_handler_module');

            load::app('modules/'.$error_module.'/actions/index.action');

            context::set('debug_error', ['message' => $e->getMessage(), 'data' => ['type' => 'public']]);

            $action_class = $error_module.'_index_action';
            $controller   = new $action_class($error_module, 'index');
            context::set_controller($controller);
            echo $controller->run();

            return;
        }

        if (conf::get('enable_log')) {
            logger::commit($log_id);
        }

        if (is_array($_SESSION['credentials']) && $_SERVER['REMOTE_ADDR'] == '::ffff:213.108.72.134') {
            load::system('log/logger');
            $programmer = in_array('programmer', (array) $_SESSION['credentials']);
        }

        if ((conf::get('enable_web_debug') || $programmer) && ($controller->get_renderer(
                ) == 'html') && $controller->get_layout()) {
            $renderer_class = $controller->get_renderer().'_render';
            $renderer       = new $renderer_class($controller);
            $output         .= $renderer->render_debug();
        }

        echo $output;
    }

    public function init()
    {
        mb_internal_encoding('utf-8');

        load::system('kernel/context');
        load::system('controller/basic_controller');

        if (conf::get('enable_log')) {
            load::system('log/logger');
        }

        $this->init_error_handling();

        load::system('db/db');
        load::system('db/db_peer');
        load::system('error/exceptions/public_exception');
    }

    public function init_error_handling()
    {
        $error_handler = conf::get('error_handler', 'debug');
        $handler_class = $error_handler.'_error';

        load::system('error/'.$handler_class);
        call_user_func("{$handler_class}::handle");
    }

    protected function get_module()
    {
        if ($_GET['module']) {
            if (!strpos($_GET['module'], '/') && ($_GET{0} != '_')) {
                return $_GET['module'];
            }
        }

        return conf::get('default_module', 'home');
    }

    protected function get_action()
    {
        if ($_GET['action'] && !strpos($_GET['action'], '.')) {
            return $_GET['action'];
        }

        return 'index';
    }
}
