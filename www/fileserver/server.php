<?
require_once getenv('FRAMEWORK_PATH') . '/library/kernel/load.php';
load::system('kernel/conf');

conf::set('project_root', realpath(dirname(__FILE__) . '/../..'));

require_once '../../conf/' . getenv('ENVIRONMENT') . '.php';
load::system('kernel/application');
require_once '../application.php';

$app = new project_application();
$app->init();

load::system('storage/storage_simple');
$storage = new storage_simple();

$q=explode('/',$_GET['q']);
$file_hash = $q[1];
db::exec("UPDATE files SET downloads=downloads+1 WHERE id=".intval($q[0]));
ini_set('zlib.output_compression', 0);

header('Content-Type: application/force-download');
if(session::get_user_id()==5968) {
	var_dump($file_hash);
	var_dump($storage->get_path($file_hash));
	exit;
}
echo $storage->get($file_hash);
