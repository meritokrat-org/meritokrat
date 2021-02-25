<?

class sign_index_action extends frontend_controller
{
    public function execute()
    {
		if ( session::is_authenticated() )
		{
			$this->redirect('/');
		}

		$this->set_layout('public');
    }
}
