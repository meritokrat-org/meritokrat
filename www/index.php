<?php

require_once __DIR__.'/../vendor/autoload.php';

if (isset($_GET['phpinfo']) && 666 === (int) $_GET['phpinfo']) {
    phpinfo();
    die;
}

session_start();

define('APP_START_TS', microtime(true));

require_once getenv('FRAMEWORK_PATH').'/library/kernel/load.php';
load::system('kernel/conf');

conf::set('project_root', __DIR__.'/..');

require_once __DIR__.'/../conf/config-'.getenv('ENVIRONMENT').'.php';
load::system('kernel/application');
require_once __DIR__.'/../apps/application.php';

$app = new project_application();

if (!array_key_exists('user_id', $_SESSION)) {
    $_REQUEST['module'] = 'sign';
}

$app->execute('frontend');
