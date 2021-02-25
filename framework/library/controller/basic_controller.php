<?php

abstract class basic_controller
{
    private $default_action = 'index';
    private $module;
    private $action;
    private $renderer = 'html';
    protected $template = null;
    private $layout = 'layout';
    private $slots = array();

    protected $authorized_access = false;
    protected $credentials = array();

    public function __construct($module, $action)
    {
        $this->module = $module;
        $this->action = $action;
    }

    public function set_header($name, $value)
    {
        header(sprintf('%s: %s', $name, $value));
    }

    public function redirect($url)
    {
        $this->set_header('location', $url);
        exit;
    }

    public function get_module()
    {
        return $this->module;
    }

    public function get_action()
    {
        return $this->action;
    }

    public function get_layout()
    {
        return $this->layout;
    }

    public function disable_layout()
    {
        $this->set_layout(null);
    }

    public function set_layout($layout)
    {
        $this->layout = $layout;
    }

    public function set_template($template)
    {
        $this->template = $template;
    }

    public function get_template_path()
    {
        return conf::get('project_root').'/apps/'.context::get_app().
            '/modules/'.str_replace('_', '/modules/', $this->get_module()).'/views/'.$this->template.'.view.php';
    }

    public function get_layout_path()
    {
        return conf::get('project_root').'/apps/'.context::get_app().'/layouts/'.$this->layout.'.view.php';
    }

    /**
     * Set output renderer
     *
     * @param string $renderer (html, json)
     */
    public function set_renderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function get_renderer()
    {
        return $this->renderer;
    }

    public function pre_init()
    {
        $this->set_template($this->get_action());

        if ($this->authorized_access) {
            if ($this->credentials && !session::has_credentials($this->credentials)) {
                $this->redirect('/');
            }

            if (!session::is_authenticated()) {
                session::set('referer', $_SERVER['REQUEST_URI']);
                $this->redirect('/sign');
            }
        }
    }

    public function init()
    {
    }

    public function post_action()
    {
    }

    abstract public function execute();

    public function render()
    {
        if ($this->renderer) {
            $render_class = $this->renderer.'_render';
            load::system('render/'.$render_class);

            $renderer = new $render_class($this);

            return $renderer->render();
        }
    }

    public function set_slot($slot, $template)
    {
        $this->slots[$slot] = $template;
    }

    public function get_slot_path($slot)
    {
        if ($path = $this->slots[$slot]) {
            if ($path{0} != '/') {
                return conf::get('project_root').'/apps/'.context::get_app().
                    '/modules/'.str_replace('_', '/modules/', $this->get_module()).'/views/'.
                    $this->slots[$slot].'.php';
            } else {
                return conf::get('project_root').'/apps/'.context::get_app().
                    '/layouts'.$this->slots[$slot].'.php';
            }
        }

        return null;
    }

    public function run()
    {
        conf::get('enable_log') ? $log_id = logger::start('Controller init') : null;

        $this->pre_init();

        conf::get('enable_log') ? logger::commit($log_id) : null;

        conf::get('enable_log') ? $log_id = logger::start('Action execute') : null;

        $this->init();
        $this->execute();
        $this->post_action();

        conf::get('enable_log') ? logger::commit($log_id) : null;

        conf::get('enable_log') ? $log_id = logger::start('Action render') : null;

        $output = $this->render();

        conf::get('enable_log') ? logger::commit($log_id) : null;

        return $output;
    }
}
