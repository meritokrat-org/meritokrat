<?

class profile_upload_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
            $this->set_renderer('ajax');
            if ( $_FILES['file']['tmp_name'] )
            {
                if($_FILES['file']['size']>10240000)
                    die('0');
                if(!strpos($_FILES['file']['type'], 'mage'))
                    die('1');

                $dir=conf::get('project_root').'/www/static/uploads/'.session::get_user_id().'/';
                if(!is_dir($dir))
                    mkdir($dir);

                $original_file_path = $_FILES['file']['tmp_name'];

                $ext = $this->get_extension($_FILES['file']['name']);
                $file_path = $dir.time().$ext;

                /*if ( $_FILES['file']['type'] != 'image/jpeg' )
                {
                        $t = tempnam('/var/tmp', 'ims');
                        copy($original_file_path, $t);
                        exec("convert  {$t} {$t}.jpg");
                        copy($t . '.jpg', $original_file_path);
                        unlink($t);
                }*/

                move_uploaded_file($original_file_path,$file_path);
                $img = '<img src="'.str_replace(conf::get('project_root').'/www', 'http://'.conf::get('server'), $file_path).'" />';
                die($img);
            }
	}

        function get_extension($filename)
	{
		return '.'.end(explode('.', $filename));
	}
}
