<?

class profile_save_photo_action extends frontend_controller
{
	public function execute()
	{
        $uploaddir = '/var/www/meritokrat/www/static/uploads/';
        $uploadfile = $uploaddir.$_FILES['avatar']['name'];

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadfile)) {

            $this->json["msg"] = "Uploaded";
            $this->json["url"] = $uploadfile;
        } else {
            $this->json["msg"] = "Error";
            $this->json["url"] = $uploadfile;
        }

        $this->set_renderer("ajax");
        $this->set_layout(null);
	}
}

?>