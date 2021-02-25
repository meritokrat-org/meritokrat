<?

load::app('modules/admin/controller');
class admin_translations_action extends admin_controller
{
	public function execute()
	{
		$this->data = translate::get_data( 'ua' );

		if ( request::get('submit') && request::get('source') && request::get('translation') )
		{
			$translations = request::get('translation');
			$php = '<? $data = array(';

			foreach ( request::get('source') as $k => $phrase )
			{
				$phrase = str_replace("'", '\\\'', $phrase);
				$translation = str_replace("'", '\\\'', $translations[$k]);
				
				$php .= "\n\t'{$phrase}' => '{$translation}',";

			}

			$php .= ');';

			$path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'i18n' . DIRECTORY_SEPARATOR . 'ua' . '.php';
			file_put_contents($path, $php);

			$this->redirect('translations');
		}
	}
}