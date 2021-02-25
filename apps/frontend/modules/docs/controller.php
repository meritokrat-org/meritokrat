<?

abstract class docs_controller extends frontend_controller
{
	#protected $authorized_access = true;
	public function init()
	{
		parent::init();

                load::model('docs/docs');
                load::action_helper('pager');

		client_helper::set_title( t('Документы') . ' | ' . conf::get('project_name') );
	}

}
