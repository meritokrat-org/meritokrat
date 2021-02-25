<?php

$start = microtime(true);

$dsn = "pgsql:host=localhost;dbname=meritokrat";

$db = new PDO($dsn, 'meritokrat', 'RRcylF0M');

$sql = "select user_id, photo_salt from user_data where photo_salt IS NOT NULL AND user_id > 11636 ORDER BY user_id ASC";
$users = $db->query($sql);

$count = $users->rowCount();
$i = 0;
$error = 0;

print "#\t";
print "Progress\t";
print "ID\t";
print "Salt\t\t";
print "Status\t\t";
print "Path\n";

print str_repeat("_", 141)."\n";

foreach ($users as $data) {
	$startItteration = microtime(true);
	$i++;

	$path_origin = get_path('profile/' . $data["user_id"] . $data['photo_salt'] . '.jpg');

	$sizes = array(
		'p' => '200',
		'm' => '160',
		'r' => '90',
		'mm' => '200',
		'mp' => '300',
		'ma' => '160',
		't' => '70',
		's' => '50',
		'sm' => '50',
		'ssm' => '30',
		'f' => '640'
	);

	if(file_exists($path_origin))
	{
		$status = true;
		foreach ($sizes as $sKey => $size) {
			$components = explode('/', photo_path($data, $data["user_id"], $sKey, "user"));
			$file_hash = implode('/', $components);

			$path_resized = get_path($file_hash);

			prepare_path($path_resized);

			exec("convert {$path_origin} -resize {$size}x {$path_resized}");
		}
	}
	else
	{
		$error++;
		$status = false;
	}

	print $i . "\t";
	print number_format(($i * 100 / $count), 1) . "%\t\t";
	print $data['user_id'] . "\t";
	print $data['photo_salt'] . "\t";
	print ($status ? "Success" : "Unsuccess") . "\t";
	print $path_origin . "\n";
}


print "Время  выполнения  скрипта: ".(microtime(true) - $start)." сек. \n";
print "Всего  обработано  записей: ".$i."\n";
print "Ошибок  обработки  скрипта: ".$error."\n";
print "Успешно обработано записей: ".($i - $error)."\n";

function photo_path( $data, $id, $size = 'p', $folder='user')
{

	if ( $data['photo_salt'] )
		return "{$size}/{$folder}/{$id}{$data['photo_salt']}.jpg";
	else
		return "{$size}/{$folder}/0.jpg";
}

function prepare_path( $path)
{
	$dir = dirname($path);

	$create = array();

	while ( !is_dir($dir) )
	{
		$create[] = $dir;
		$dir = dirname($dir);
	}

	$create = array_reverse($create);

	foreach ( $create as $dir )
	{
		mkdir($dir, 0777);
		chmod($dir, 0777);
	}
}

function get_path( $key, $absolute_path = true )
{
	$hash = md5($key);
	$file_path = '';

	for ( $i = 0; $i < 4; $i ++ )
		$file_path .= substr($hash, $i * 2, 2) . '/';

	$file_path .= md5($hash);

	$path = $file_path;
	if ( $absolute_path )
	{
		$path = '/var/www/meritokrat/data/storage/'.$path;
	}

	return $path;
}
