<?

load::system('modules/controller');

class debug_db_new_migration_action extends kernel_controller
{
	public function execute()
	{
		if ( $sql = request::get_string('sql') )
		{
			if ( $sql{strlen($sql) - 1} != ';' )
			{
				$sql .= ';';
			}

			$dir = conf::get('project_root') . '/data/sql';

			$d = opendir($dir);
			$max_index = 0;
			while ( $file = readdir($d) )
			{
				$file_path = $dir . '/' . $file;

				if ( is_file( $file_path ) && strpos($file_path, '.sql') )
				{
					$details = explode('-', $file);

					$details[0] = (int)$details[0];

					if ( $details[0] > $max_index )
					{
						$max_index = $details[0];
					}
				}
			}

			$max_index++;
			$new_index = sprintf('%04d', $max_index);

			$new_migration = $new_index . '-' . getenv('ENVIRONMENT') . '.sql';
			file_put_contents($dir . '/' . $new_migration, $sql);
			chmod($dir . '/' . $new_migration, 0777);

			$this->set_renderer('ajax');
		}
	}
}