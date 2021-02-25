<?

$conf_dir = realpath( dirname(__FILE__) . '/../conf');

$env = 'dev-golotyuk';
$framework_path = dirname(__FILE__) . '/../framework';

putenv( 'FRAMEWORK_PATH=' . $framework_path );
putenv( 'ENVIRONMENT=' . $env );

require_once getenv('FRAMEWORK_PATH') . '/library/kernel/load.php';
load::system('kernel/conf');

conf::set('project_root', realpath(dirname(__FILE__) . '/..'));

require_once dirname(__FILE__) . '/../conf/' . getenv('ENVIRONMENT') . '.php';

load::system('kernel/application');
load::system('shell/task');

load::system('db/db_peer_postgre');

shell_task::run();