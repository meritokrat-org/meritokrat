<?php

session_start();

define('APP_START_TS', microtime(true));

require_once getenv('FRAMEWORK_PATH') . '/library/kernel/load.php';
load::system('kernel/conf');

conf::set('project_root', realpath(dirname(__FILE__) . '/..'));

require_once '../conf/config-' . getenv('ENVIRONMENT') . '.php';
load::system('kernel/application');
require_once '../apps/application.php';

$app = new project_application();

if (!$_SESSION['user_id'])
    $_REQUEST['module'] = 'sign';

$app->execute('frontend');