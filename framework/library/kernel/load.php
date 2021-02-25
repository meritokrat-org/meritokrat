<?php

class load
{
    public static function app($name)
    {
        $path = implode(
            DIRECTORY_SEPARATOR,
            [
                conf::get('project_root'),
                'apps',
                context::get_app(),
                sprintf('%s.php', $name),
            ]
        );

        if (!is_file($path)) {
            throw new load_exception("Cannot load \"{$name}\" ({$path})");
        }

        include_once $path;
    }

    public static function task($task_name)
    {
        $path = getenv('FRAMEWORK_PATH').'/library/shell/tasks/'.$task_name.'.php';

        if (!is_file($path)) {
            $path = conf::get('project_root').DIRECTORY_SEPARATOR.'tasks/tasks/'.$task_name.'.php';
        }

        if (!is_file($path)) {
            throw new load_exception("Task \"{$task_name}\" is unknown ({$path})");
        }

        include_once $path;
    }

    public static function model($name)
    {
        self::lib('models/'.$name.'.peer');
    }

    public static function lib($name)
    {
        require_once conf::get('project_root').DIRECTORY_SEPARATOR.'lib/'.$name.'.php';
    }

    public static function view_helper($name, $system = false)
    {
        if ($system) {
            self::system('helpers/view/'.$name.'.helper');
        } else {
            self::lib('helpers/view/'.$name.'.helper');
        }
    }

    public static function system($name)
    {
        $path = getenv('FRAMEWORK_PATH').DIRECTORY_SEPARATOR.'library'.DIRECTORY_SEPARATOR.$name.'.php';
        require_once $path;
    }

    public static function action_helper($name, $system = true)
    {
        if ($system) {
            self::system('helpers/action/'.$name.'.helper');
        } else {
            self::lib('helpers/action/'.$name.'.helper');
        }
    }

    public static function form($name)
    {
        self::lib('forms/'.$name.'.form');
    }
}

class load_exception extends Exception
{

}