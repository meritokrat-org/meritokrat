<?

class profile_branding_action extends frontend_controller
{
	protected $authorized_access = true;

	public function execute()
	{
	    
	    $action = request::get('act');

	    if(in_array($action, array('upload', 'brand'))) {
		$this->set_renderer('ajax');
		$this->json = $this->$action();
	    }
		
	}

	private function upload() {
	    if ($_FILES['file']['tmp_name'] )
                { 
                    $dir=conf::get('project_root').'/www/static/branding/'.session::get_user_id().'/';
                    if(!is_dir($dir))
                        mkdir($dir);
                    $original_file_path=$_FILES['file']['tmp_name'];
		    if($handle = opendir($dir)) 
			while(false!==($file = readdir($handle))) 
			    if(!in_array($file, array('.','..')) && is_file($dir.$file))
				unlink($dir.$file);
		    $fileName =  substr(md5(time()),0,8).'.jpg';   
                    $file_path=$dir.$fileName;
                    
                    if ( $_FILES['file']['type'] != 'image/jpeg' )
                    {
                            $t = tempnam('/var/tmp', 'ims');
                            copy($original_file_path, $t);
                            exec("convert  {$t} {$t}.jpg");
                            copy($t . '.jpg', $original_file_path);
                            unlink($t);
                    }

                    move_uploaded_file($original_file_path,$file_path);
		    return $fileName;
		}
	}
	
	private function brand() {
//		$filePath = conf::get('project_root').'/www/static/branding/'.session::get_user_id().'/'.request::get('file');
//		$crop = request::get('crop');
//
//		if(file_exists($filePath) && count($crop)==4) {
//		    $image = new Imagick($filePath);
//		    $watermark = new Imagick('/var/www/meritokrat/www/static/images/logo2.png');
//
////		    $template['h'] = (200/$image->getImageHeight())*$image->getImageWidth();
//		    $template['w'] = 200*($crop['w']/100);
//
//		    $scaleX = $image->getImageWidth()/100;
//		    $scaleY = $image->getImageHeight()/100;
//
//		    $crop['x'] = ($crop['x']*$scaleX);
//		    $crop['y'] = ($crop['y']*$scaleY);
//		    $crop['w'] = ($crop['w']*$scaleX);
//		    $crop['h'] = ($crop['h']*$scaleY);
//
//		    $image->cropImage($crop['w'], $crop['h'], $crop['x'],$crop['y']);
//		    $image->resizeImage($template['w'], 0, Imagick::FILTER_LANCZOS, 1);
//		    $image->compositeImage($watermark, Imagick::COMPOSITE_DEFAULT, 0.95*$template['w']-$watermark->getImageWidth(), 0.95*$image->getImageHeight()-$watermark->getImageHeight());
//
//		    header("Content-Disposition: attachment; filename=".  substr(md5(time), 0,8).".jpg");
//		    header("Content-Type: application/x-force-download; name=\"Брендоване_фото\"");
//		    echo $image->getImageBlob();
//		    exit;
//
//		}
	}
	
        function get_extension($filename)
	{
		$x = explode('.', $filename);
		return '.'.end($x);
	}
}
