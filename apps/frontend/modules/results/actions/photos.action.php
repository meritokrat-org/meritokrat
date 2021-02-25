<?

class results_photos_action extends frontend_controller
{
	protected $authorized_access = false;
	public function execute()
	{
		if (!session::has_credential('admin'))
		{
			throw new public_exception('Недостаточно прав.');
		}
                $this->list = db::get_rows("SELECT user_id,
                  information_avtonumbers_photos
                  FROM user_desktop
                  WHERE
                  information_avtonumbers_photos!='a:0:{}'");   
               # print_r($this->list);
        } 
}