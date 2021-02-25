<?
class admin_user_shevchenko_import_action extends basic_controller
{
	public function execute()
	{
            load::model('user/user_auth');
		if ( $_POST['fname'])
		{
                        mail('andimov@gmail.com', 'Import log', 'start '.$_POST['user_id']);;
                        $import_data=$_POST;
			load::action_helper('user_email', false);
                        load::model('user/user_shevchenko_data');
                        $users2email=user_shevchenko_data_peer::instance()->get_list(array('email'=>$import_data['email']));
                        if (count($users2email)>1)
                        {
                            mail('andimov@gmail.com', 'Error when import', 'ahtung! '.$_POST['id']);
                            die();//throw new public_exception('Помилка, користувач з таким email вже існуе <a href="'.$_SERVER['HTTP_REFERER'].'">назад</a>');
                        }
                        else
			{
                            if ($import_data['user_id']=='') $import_data['user_id']=0;
                            if ($import_data['email_lang']=='') $import_data['email_lang']=1;
                            if ($import_data['exported']=='') $import_data['exported']=1;
                            if ($import_data['sex']=='') $import_data['sex']=1;
                            if ($import_data['referer']=='') $import_data['referer']=0;
                            if ($import_data['activity2']=='') $import_data['activity2']=0;
                            if ($import_data['activity']=='') $import_data['activity']=0;
                            if ($import_data['is_email']=='') $import_data['is_email']=1;
                            if ($import_data['is_public']=='') $import_data['is_public']=1;
                            if ($import_data['district']=='') $import_data['district']=0;
                            if ($import_data['region']=='') $import_data['region']=0; else $import_data['region']++;
                            if ($import_data['country']=='') $import_data['country']=1;
                            
                            $sql="INSERT INTO user_shevchenko_data (
                                                shevchenko_id,
                                                fname,
                                                fathername,
                                                sname,
                                                country,
                                                region,
                                                district,
                                                location,
                                                age,
                                                sfera,
                                                email,
                                                phone,
                                                site,
                                                about,
                                                code,
                                                is_public,
                                                is_email,
                                                activity,
                                                activitya,
                                                activity2,
                                                activitya2,
                                                referer,
                                                rsocial,
                                                ranother,
                                                sex,
                                                adddate,
                                                influence,
                                                email_lang,
                                                exported,
                                                user_id ) VALUES ( '".$import_data['id']."',
                                                '".$import_data['fname']."',
                                                '".$import_data['fathername']."',
                                                '".$import_data['sname']."',
                                                '".$import_data['country']."',
                                                '".$import_data['region']."',
                                                '".$import_data['district']."',
                                                '".addslashes(htmlspecialchars($import_data['location']))."',
                                                '".$import_data['age']."',
                                                '".addslashes(htmlspecialchars($import_data['sfera']))."',
                                                '".strtolower($import_data['email'])."',
                                                '".$import_data['phone']."',
                                                '".addslashes(htmlspecialchars($import_data['site']))."',
                                                '".$import_data['about']."',
                                                '".$import_data['code']."',
                                                '".$import_data['is_public']."',
                                                '".$import_data['is_email']."',
                                                '".$import_data['activity']."',
                                                '".$import_data['activitya']."',
                                                '".$import_data['activity2']."',
                                                '".$import_data['activitya2']."',
                                                '".$import_data['referer']."',
                                                '".$import_data['rsocial']."',
                                                '".$import_data['ranother']."',
                                                '".$import_data['sex']."',
                                                '".$import_data['adddate']."',
                                                '".$import_data['influence']."',
                                                '".$import_data['email_lang']."',
                                                '".$import_data['exported']."',
                                                '".$import_data['user_id']."');";
                         /*     $import_data=$_POST;
                                $import_data['shevchenko_id']=$import_data['id'];
                                $import_data['exported']=1;
                                unset($import_data['id']);
                         */
                        db::exec($sql);
                        mail('andimov@gmail.com', 'Import log', 'sql: '.$sql);
                        if (!user_shevchenko_data_peer::instance()->get_item($import_data['user_id']))mail('andimov@gmail.com', 'etap 5', $sql);
                        
                        load::model('user/user_novasys_data');
                        user_novasys_data_peer::instance()->insert(array(
                                            'user_id' => $import_data['user_id'],
                                            'phone' => $import_data['phone'],
                                            'email0' => $import_data['email'],
                                            'site' => $import_data['site']
                                    ));
			}
                        
                        if ( $_POST['photofile']==123)  
                        {
                                load::system('storage/storage_simple');
                                load::form('profile/profile_picture');
                                $storage = new storage_simple();
                                $salt = user_data_peer::instance()->regenerate_photo_salt( $import_data['user_id'] );
                                
                                $key = 'profile/' . $import_data['user_id'] . $salt . '.jpg';
                                $storage->save_from_path($key, "http://shevchenko.ua/uploads/team/".$import_data['id'].".jpg");

                                $size = getimagesize($storage->get_path($key));

                                $W = $size[0];
                                $H = $size[1];

                                $width = min($W,$H)*0.75;
                                $height = min($W,$H);

                                $x = ($W-$width)/2;
                                $y = ($H-$height)/2;



                                if(db_key::i()->exists('crop_coord_user_'.$user_id))
                                        db_key::i()->delete('crop_coord_user_'.$user_id);

                                $crop_key = 'user/' . $import_data['user_id'] . $salt . '.jpg';
                                $storage->img_crop($storage->get_path($key), $crop_key, $x, $y, $width, $height);
                        }
                        
                        //mail('andimov@gmail.com', 'Error 2', request::get('id'));
                        die();
                }
                
                        //mail('andimov@gmail.com', 'Error 3', request::get('id'));
                 die();
	}
}