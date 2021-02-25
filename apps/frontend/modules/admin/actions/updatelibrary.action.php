<?

class admin_updatelibrary_action extends basic_controller
{
	public function execute()
	{
		load::view_helper('user');
		load::system('storage/storage_simple');
		load::model('groups/files');
		$files = db::get_rows("SELECT * FROM groups_files WHERE exts!='' and url!='' and files='a:0:{}'");

		foreach ($files as $f) {
			$files = array();
			$infiles = array();
			if (isset($f['exts'])) {
				$arr = array_unique(explode(",", $f['exts']));
				foreach ($arr as $ext) {
					$infiles[] = str_replace($arr[0], $ext, $f['url']);
				}
			}
			foreach ($infiles as $in) {
				$storage = new storage_simple();
				$salt = groups_files_peer::generate_file_salt();
				$file = "/var/www/meritokrat/www/" . $in;
				if (!file_exists($file)) {
					echo "Нет оригинала " . $f['id'] . "<br/>";
					continue;
				}
				$path = $storage->get_path($salt);
				$storage->prepare_path($path);
				copy($file, $path);
				$filename = end(explode("/", $file));
				$files[] = array("name" => $filename,
					"salt" => $salt);
				if (!file_exists($path)) {
					echo "Нет копии " . $path . "<br/>";
					continue;
				}
			}
			groups_files_peer::instance()->update(array(
				'id' => $f['id'],
				'user_id' => $f['user_id'],
				'files' => serialize($files)
			));

		}
	}
}
