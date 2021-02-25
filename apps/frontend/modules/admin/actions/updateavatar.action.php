<?

class admin_updateavatar_action extends basic_controller
{
	public function execute()
	{
        load::view_helper('user');
       #$users = user_auth_peer::instance()->get_list(array(),array('limit' => 100 , 'offset' => 0));
       $users = db::get_cols('SELECT id FROM user_auth LIMIT :limit OFFSET :offset;', array('limit' => 100 , 'offset' => 1000), $this->connection_name);
       foreach($users as $id)
       { 
           #echo user_helper::photo_path( $id);
		$data = user_data_peer::instance()->get_item($id);
                if($data['photo_salt']) {
                   # print_r($data);

               # echo $data['photo_salt'] ;
               echo user_helper::photo( $id, 'p' );
                $components = explode('/', $_GET['q']);
$file_hash = implode('/', $components);
		$file=$this->get_path($data['photo_salt'] );  
                if(file_exists($file)) echo "fFDFDD";
              # echo $file;# exit();

               }
       } die();
       }

        function get_path( $key, $absolute_path = true )
	{
		$hash = md5($key);

		$file_path = '';

		for ( $i = 0; $i < 4; $i ++ )
		{
			$file_path .= substr($hash, $i * 2, 2) . '/';
		}

		$file_path .= md5($hash);

		if ( $this->enable_extensions )
		{
			$file_path .= '.' . pathinfo($key, PATHINFO_EXTENSION);
		}

		$path = $file_path;
		if ( $absolute_path )
		{
			$path = conf::get('file_storage_path') . '/' . $path;
		}

		return $path;
	}
}
