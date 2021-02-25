<?

load::system('modules/controller');

class debug_mail_index_action extends kernel_controller
{
	public function execute()
	{
		$dir = conf::get('project_root') . '/data/debug/mails';

		$this->list = array();

		$d = opendir($dir);
		while ( $file = readdir($d) )
		{
			$file_path = $dir . '/' . $file;

			if ( is_file( $file_path ) )
			{
				$details = explode('.', $file);
				$this->list[$details[0]] = array(
					'subject' => $details[0],
					'data' => file_get_contents($file_path)
				);
			}
		}

		krsort($this->list);
	}
}