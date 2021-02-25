<?
load::app('modules/admin/controller');
class admin_mailtpls_action extends admin_controller
{
	public function execute()
	{
               $path = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'registration' . '.tpl';
               $spath = conf::get('project_root') . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'mail_tpls' . DIRECTORY_SEPARATOR . 'subject.registration' . '.tpl';
               
               if ( request::get('submit') && request::get('tpl') )
		{
			$tpl = request::get('tpl');
			$subject = request::get('subject');
			file_put_contents($path, stripslashes($tpl));
			file_put_contents($spath, stripslashes($subject));

			$this->redirect('mailtpls');
		}
                $this->tpl=file_get_contents($path);
                $this->subject=file_get_contents($spath);
	}
}