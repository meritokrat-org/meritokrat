<?

class profile_photobranding_action extends basic_controller
{
	public function execute()
	{
                load::view_helper('user');
                load::model('feed/feed');
                //$this->set_layout('');                
                $_SESSION['temp_dir_id']=rand(1000,9999);
		if ( request::get('submit') && $_FILES['file']['tmp_name'] )
                { #print_R($_FILES);
                    $dir=conf::get('project_root').'/www/static/temp/'.session::get('temp_dir_id').'/';
                    if(!is_dir($dir)) mkdir($dir);
                    $original_file_path=$_FILES['file']['tmp_name'];
                    #preg_match('/(\S+)\.\S+$/', $_FILES['file']['name'], $out);
                    $dir=$dir.substr(md5(time()), 0,8).'/';
                    mkdir($dir);
                    $file_path=$dir.'original.jpg';
                    
                    if ( $_FILES['file']['type'] != 'image/jpeg' )
                    {
                            $t = tempnam('/var/tmp', 'ims');
                            copy($original_file_path, $t);
                            exec("convert  {$t} {$t}.jpg");
                            copy($t . '.jpg', $original_file_path);
                            unlink($t);
                    }

                    move_uploaded_file($original_file_path,$file_path);
                 
                    for($i=2;$i<=2;$i++){
                    $file=$dir.$i.'.jpg';
                    $watermark=conf::get('project_root').'/www/static/images/logo'.$i.'.png';
                    exec("convert  -quality 100 {$file_path} -resize 200 {$file}");//
//                    exec("composite -quality 100 -gravity southeast {$watermark} {$file} {$file}");
                    }
                    $this->file_path=str_replace(conf::get('project_root').'/www', '', $dir);
                    #$this->json = context::get('image_server') . user_helper::photo_path($user_id);
                }
	}

        function get_extension($filename)
	{
		$x = explode('.', $filename);
		return '.'.end($x);
	}
}
