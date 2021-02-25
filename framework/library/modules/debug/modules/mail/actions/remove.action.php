<?

class debug_mail_remove_action extends frontend_controller
{
	public function execute()
	{
		$ts = request::get('ts');
		$dir = conf::get('project_root') . '/data/debug/mails';

		$d = opendir($dir);
		while ( $file = readdir($d) )
		{
			$file_path = $dir . '/' . $file;

			if ( is_file( $file_path ) )
			{
				$details = explode('.', $file);
				if ( $ts && ( $details[1] == $ts ) )
				{
					unlink($file_path);
					break;
				}
				else
				{
					unlink($file_path);
				}
			}
		}

		$this->set_renderer('ajax');
		$this->json = array();
	}
}