<?
ignore_user_abort(1);
set_time_limit(0);
//die();
load::app('modules/admin/controller');
class admin_one_send_action extends frontend_controller
{
        public function execute()
	{   
            
            load::model('acquiring_payments');
            acquiring_payments_peer::instance()->payment_proccess(300);
            
            /*$users=db::get_cols("SELECT id FROM user_auth");
            $blog_posts=db::get_cols("SELECT id FROM blogs_posts WHERE user_id in (5,31)");
            foreach($blog_posts as $post)
            {
                $viewers='';
                foreach ($users as $user)
                {
                    if (db_key::i()->exists('post_viewed:'.$post.':'.$user))
                    {
                          $viewers.=','.$user;
                    }
                }
                
                db::exec('INSERT INTO blogs_posts_views ("post_id", "users") VALUES ('.$post.",'".substr($viewers,1)."')");
                
            }
            foreach ($users as $user)
            {
                $posts='';
                foreach($blog_posts as $post)
                {
                    if (db_key::i()->exists('post_viewed:'.$post.':'.$user))
                    {
                          $posts.=','.$post;
                    }
                }
                
                db::exec('INSERT INTO blogs_users_views ("user_id", "posts") VALUES ('.$user.",'".substr($posts,1)."')");
                
            }*/
            
            die();
            /*
                load::model('messages/messages');

                $this->friends = array(29,75,83,1326,1484,63,68,2973,2937,3307,3243,1575,867,2441,999,9607,3496,3675,179,2763,3099,1854,1042,47,620,1931,465,4039,4021,4143,4139,4317,4290,66,485,4351,4510,4445,4527,2220,4819,5006,4792,5076,5207,686,5328,5192,5363,5663,647,1774,9639,5620,4990,5829,5927,5851,5901,5911,5932,2502,5549,5657,2329,2448,4811,6364,6518,6529,6092,6903,6926,6916,6930,8090,7066,7100,6519,7138,7195,7258,7240,6982,7272,7188,7275,7274,7260,7360,7434,7433,7455,7462,7330,7490,7496,7543,7579,7410,7531,7600,7592,6745,7585,7605,7612,7614,2639,1414,7680,7679,7712,7702,7675,7862,7880,7878,7858,6114,6875,7697,9078,7808,4183,8020,7930,7965,8017,8013,7998,8157,8128,8110,8199,8202,8210,8225,8269,8120,100,8375,9160,8405,8402,623,415,8103,8370,41,8453,8455,8422,9427,8526,8536,9562,8502,8552,8532,8429,8550,8553,8565,8638,8616,9564,8641,8535,8689,9565,5082,458,8736,9568,8785,8807,8906,8881,8734,8893,8958,8954,8978,8994,8990,8106,9577,1686,8815,7256,3875,8668,8038,4191,10440,201,145,2159,1439,7291,224,1628,1465,1891,4795,4126,85,9168,9136,9137,9195,9244,9226,9241,9204,9247,81,2051,4107,6912,483,722,287,2079,6782,8278,6220,1913,2168,5907,1299,5391,8956,7525,739,1744,7513,5348,7356,377,969,8601,9755,9741,8914,2555,5185,5538,8504,4158,8999,8772,1443,7166,6448,6584,9625,8618,1436,1174,7607,8022,7776,8922,8598,7617,8794,4293,5382,4801,6627,1752,5904,9919,9923,3433,3231,8996,39,6156,7813,9958,5693,9997,9980,245,9045,1146,10014,10091,9063,8907,2702,8117,10448,4069,8793,1504,3789,8,5859,9138,8309,3827,4528,439,7006,4621,8023,1503,1006,2587,1413,7279,6219,4262,9471,9234,3800,9224,3600,498,450,7764,8175,1184,4936,6160,809,836,1056,9566,9638,2679,431,32);
                $sender_id=2641;
                $this->message = '<p>Шановні керівники парторганізацій та координатори,
                    <br />За цим посиланням <a href="https://meritokrat.org/help/index?InstructionDenNezalezhnosti">https://meritokrat.org/help/index?InstructionDenNezalezhnosti</a> розміщена<br />Інструкція по підготовці та проведенню акції МПУ до Дня Незалежності.
                    У ній ви знайдете відповіді на більшість своїх запитань і усю необхідну інформацію. 
                    Інструкцію у doc-форматі можна завантажити за посиланням <a href="https://meritokrat.org/uploads/tinymce/instrukcija24.doc">https://meritokrat.org/uploads/tinymce/instrukcija24.doc</a>
                    <br />Отримання цього листа ОБОВ`ЯЗКОВО підтвердіть відповідю у внутрішньому повідомленні.<br /><br />З повагою,<br />Михайло Чаплига</p>';

                $this->receivers = $this->friends;
                if ( count($this->receivers)>0 && $this->message != '' )
                {
                    foreach($this->receivers as $receiver_id)
                    {
                        if(!intval($receiver_id))
                            continue;
                        $id = messages_peer::instance()->add(array(
                                'sender_id' => $sender_id,
                                'receiver_id' => $receiver_id,
                                'body' => trim($this->message)
                        ),true,0);
                        load::action_helper('user_email', false);
                        $options = array(
                                    '%text%' => tag_helper::get_short(trim(strip_tags($this->message)),120),
                                    '%link%' => 'http://' . context::get('host') . '/messages/view?id=' . $id
                                    );
                        user_email_helper::send_sys('messages_compose',$receiver_id,$sender_id,$options);
                    }
                }
                
                
                
              /* load::model('user/user_auth');
               load::model('user/user_data');
                foreach (user_data_peer::instance()->get_list(array('gender'=>'f')) as $user_id)
                {   
                    $user_auth_data='';
                    $user_data='';
                    $user_auth_data=user_auth_peer::instance()->get_item($user_id);
                    if (db::get_scalar("SELECT id FROM email_users WHERE email ILIKE '$user_auth_data[email]'")) $email_id=db::get_scalar("SELECT id FROM email_users WHERE email ILIKE '$user_auth_data[email]'");
                    else
                    {
                        $user_data=user_data_peer::instance()->get_item($user_id);
                         $email_id=db::exec('INSERT INTO "public"."email_users" ("email", "first_name", "last_name", "blacklisted") VALUES (\''.strtolower($user_auth_data["email"]).'\', \''.$user_data["first_name"].'\', \''.$user_data["last_name"].'\', 0)   RETURNING id ;');
                    }
                    if (is_int($email_id)) $sql='INSERT INTO "public"."email_lists_users" ("user_id", "list_id") VALUES (\''.$email_id.'\', \'306\');';
                     db::exec($sql);
                    

                }
          /*     load::model('user/user_auth');
                load::model('blogs/comments');
                load::model('blogs/posts');
                load::model('groups/topics');
                load::model('groups/topics_messages');
            $update_arr=db::get_rows("SELECT * FROM groups_topics WHERE blogpost_id is NULL");
            foreach ($update_arr as $key=>$topic)
            {
                echo $topic['id'].'-';
                $first_message=db::get_row("SELECT * FROM groups_topics_messages WHERE topic_id=".$topic['id']." ORDER by id ASC LIMIT 1");
                //создаем массив данных для создания записи в блогах
                if ($first_message)
                {
                    $data = array(
                        'title' => $topic['topic'],
                        'body' => $first_message['text'],
                        'text_rendered' => $first_message['text'],
                        'preview' => nl2br(text_helper::smart_trim(strip_tags($first_message['text']), 256)),
                        'public' => session::has_credential('editor'),
                        'type' => 5,
                        'group_id' => $topic['group_id'],
                        'views' => $topic['views']
                    );
                    $data['created_ts'] = $first_message['created_ts'];
                    $data['sort_ts'] = $first_message['created_ts'];
                    $data['user_id'] = $first_message['user_id'];
                    //добавляем пост в общую таблицу
                    $post_id = blogs_posts_peer::instance()->insert( $data, false, blogs_posts_peer::TYPE_GROUP_POST );
                    blogs_posts_peer::instance()->rate($post_id, $first_message['user_id']);
                    echo $post_id;
                    db::exec("UPDATE groups_topics SET blogpost_id=".$post_id." WHERE id=".$topic['id']."");        

                    //берем все сообщения в обсуждении
                    $messages=db::get_rows("SELECT * FROM groups_topics_messages WHERE topic_id=".$topic['id']." and id!=".$first_message['id']." ORDER by id ASC");
                    $users=db::get_cols("SELECT id FROM user_auth");
                    foreach ($messages as $key=>$message)
                        {

                               foreach ($users as $key=>$user_id) if (groups_topics_messages_peer::instance()->has_rated($message['id'], $user_id)) db_key::i()->set('blog_comment_rate:' . $message['id'] . ':' . $user_id, true);                           
                                $data = array(
                                        'user_id' => $message['user_id'],
                                        'text' => $message['text'],
                                        'created_ts' => $message['created_ts'],
                                        'rate' => $message['rate'],
                                        'post_id' => $post_id
                                );

                                $this->id = blogs_comments_peer::instance()->insert($data);
                                blogs_comments_peer::instance()->rate($this->id, $message['user_id']);
                        }
                }
                
             }
           * */
           // var_dump($errors);
            
            /*
            foreach ($update_arr as $mails)
            {
               //var_dump($mails);
               if ($id_user=db::get_scalar("SELECT id FROM email_users WHERE email ILIKE '".$mails['email']."'")) echo "yes".$id_user;
               else 
               {
                   db::exec("INSERT INTO email_users (email, first_name, last_name, blacklisted) VALUES ('".$mails['email']."', '".$mails['first_name']."', '".$mails['last_name']."', 0)");
                   $id_user=db::get_scalar("SELECT id FROM email_users WHERE email ILIKE '".$mails['email']."'");
               }
               db::exec("INSERT INTO email_lists_users VALUES ('".$id_user."', '305')");
               
               //die("UPDATE user_shevchenko_data SET shevchenko_id=".$email['id']." WHERE email='".trim(strtolower($email['email']))."'");
              
            }
            var_dump($errors);
            
             * $photo[$photo['user_id']]=file_get_contents("https://image.meritokrat.org/o/user/".$photo['user_id'].$photo['photo_salt'].'.jpg');
               if (strpos($photo[$photo['user_id']],'Fatal error')) 
               {
                $sh_id=db::get_scalar("SELECT max(shevchenko_id) FROM user_shevchenko_data WHERE user_id=".$photo['user_id']);
                   load::system('storage/storage_simple');

                                        load::form('profile/profile_picture');
                                        $storage = new storage_simple();
                                        $salt = user_data_peer::instance()->regenerate_photo_salt( $photo['user_id'] );
                                        $key = 'user/' . $photo['user_id'] . $salt . '.jpg';
                                        $storage->save_from_path($key, "http://shevchenko.ua/uploads/team/".$sh_id.".jpg");
              //echo "https://image.meritokrat.org/o/user/".$photo['user_id'].$photo['photo_salt'].'.jpg<br>https://image.meritokrat.org/o/user/'.$photo['user_id'].$salt.'.jpg<br>'.$sh_id.'http://shevchenko.ua/uploads/team/'.$sh_id;
              //die();
            
            $update_arr=db::get_rows("SELECT id, email FROM ns_temp_recomendations WHERE meaby_user_id is NULL");
            //$update_arr=db::get_rows('SELECT * FROM temp_shev_mails');
            foreach ($update_arr as $key=>$userr)
            {
               $user_id=db::get_scalar("SELECT id FROM user_auth WHERE email='".$userr['email']."'");
               if ($user_id>0) db::exec("UPDATE ns_temp_recomendations SET meaby_user_id=".$user_id."  WHERE email='".$userr['email']."' AND  meaby_user_id is NULL");
               else $errors[]=$userr['email'].'<br>';
               //die("UPDATE user_shevchenko_data SET shevchenko_id=".$email['id']." WHERE email='".trim(strtolower($email['email']))."'");
            }
            var_dump($errors);
                
            
            $update_arr=db::get_rows("SELECT * FROM ns_temp_recomendations");
            //$update_arr=db::get_rows('SELECT * FROM temp_shev_mails');
            foreach ($update_arr as $key=>$userr)
            {
               //$sql="INSERT INTO user_recomendations VALUES (nextval('user_recomendations_id_seq'::regclass), ".$userr['inviter_id'].", '".$userr['first_name']."', '".$userr['last_name']."', ''::text, 1, 1, 1, ''::character varying, 'm')";
               //db::exec($sql);
              //echo $sql.'<br>';
               if ($userr['type']==0) $sql="UPDATE user_auth SET recomended_by=".$userr['inviter_id']." WHERE id=".$userr['meaby_user_id'];
               else $sql="UPDATE user_auth SET invited_by=".$userr['inviter_id']." WHERE id=".$userr['meaby_user_id'];
               //die($sql);
               db::exec($sql);
               $errors[]="UPDATE user_auth SET invited_by=".$userr['inviter_id']." WHERE id=".$userr['meaby_user_id']."<br>";
               //die("UPDATE user_shevchenko_data SET shevchenko_id=".$email['id']." WHERE email='".trim(strtolower($email['email']))."'");
            }
            var_dump($errors);
            
            /*
               load::model('user/user_auth');
                load::model('user/user_data');
                load::action_helper('user_email',false);
                $today_start=strtotime(date("Y-m-d"));
                $first_date_start=$today_start-5*24*60*60;
                $first_date_end=$first_date_start+24*60*60;
                $data = db::get_cols("SELECT id FROM user_auth WHERE activated_ts>".(strtotime(date("Y-m-d"))-4*24*60*60)." AND active is TRUE");
                if(!is_array($data)) die();
                foreach ( $data as $id )
                        {
                               
                                $user_data = user_data_peer::instance()->get_item($id);
                                
                                $options = array('%name%' => $user_data['first_name']." ".$user_data['last_name']);
                                user_email_helper::send_sys('sign_up',$id,false,$options);
                        }
                        var_dump($data);
        die();
            /*
            
             $update_arr=db::get_cols("SELECT user_id FROM user_novasys_data WHERE phone=''");
            //$update_arr=db::get_rows('SELECT * FROM temp_shev_mails');
            foreach ($update_arr as $key=>$user)
            {              
                
               $for_update_arr=db::get_rows('SELECT phone FROM user_shevchenko_data WHERE id in (SELECT )');
               $user_id=db::get_scalar("SELECT id FROM user_auth WHERE email='".trim(strtolower($user['email0']))."'");
               if ($user_id>0) db::exec("UPDATE user_novasys_data SET user_id=".$user_id."  WHERE email='".$user['email0']."' AND user_id=0");
               else $errors[]="SELECT id FROM user_auth WHERE email='".trim(strtolower($user['email0']))."' LIMIT 1 <br>";
               //die("UPDATE user_shevchenko_data SET shevchenko_id=".$email['id']." WHERE email='".trim(strtolower($email['email']))."'");
            }
            var_dump($errors); 
            /*            $desktop=array(
                'function_secretariat_member' => 22,
                'function_logistic_coordinator' =>18,	
                'function_vnz_representative' =>14,		
                'function_orgcommittee_member' =>1,
                'function_regional_koordinator' =>9,
                'function_district_koordinator' =>10,
                'function_development_koordinator' =>5
            );
            load::model('user/user_desktop');
            $all_desktops=user_desktop_peer::instance()->get_list();
            foreach($all_desktops as $id)
            {
                $user_desktop=user_desktop_peer::instance()->get_item($id);
                $function_update='{';
                              foreach ($desktop as $function_name=>$function_id) if ($user_desktop[$function_name]==1) $function_update.= $function_id.',';
                $function_update.='}';
                user_desktop_peer::instance()->update(array(
                        'user_id' => $id,
                        'functions' => str_replace(',}','}',$function_update)
                ));
*/
            /* $without_desktop_list=db::get_cols("select id from user_auth where id not in (SELECT user_id from user_desktop)");
            foreach($without_desktop_list as $id)
            {
				load::model('user/user_desktop');
				user_desktop_peer::instance()->insert(array(
					'user_id' => $id
				));
            }
             // db::exec("UPDATE user_desktop SET people_recommended=people_recommended+1 WHERE user_id=:user_id",array('user_id'=>session::get_user_id()));
     //   }
//        public function photos() {
            ini_set('max_execution_time', 0);
            $this->disable_layout();
            $this->set_renderer(false);
            load::model('user/user_data');
            load::system('storage/storage_simple');
            $storage = new storage_simple();
            if(db_key::i()->exists('last_changed_photo_id'))
                    $last_id = db_key::i()->get('last_changed_photo_id');
            else
                $last_id = 0;
            $user_ids = db::get_rows("SELECT user_id, photo_salt FROM user_data WHERE (photo_salt!='' AND user_id>".$last_id.") ORDER BY user_id ASC");
            
            foreach($user_ids as $num=>$user_data) {
                
                $user_id = $user_data['user_id'];
                $old_salt = $user_data['photo_salt'];
                $old_key = 'user/'.$user_id.$old_salt.'.jpg';

                if(file_exists($storage->get_path($old_key))) {

                    $new_salt = substr(md5(microtime(true)), 0, 8);
                    $new_key = 'profile/'.$user_id.$new_salt.'.jpg';
                    $crop_key = 'user/'.$user_id.$new_salt.'.jpg';

                    @$new_path = $storage->get_path($new_key);
                    @$storage->prepare_path($new_path);
                    $errors = false;

                    if(copy($storage->get_path($old_key),$storage->get_path($new_key))!=false) {
                        echo "<h3>UID=".$user_id."</h3><br>";
                        echo "File ".$storage->get_path($old_key).' was copy to '.$storage->get_path($new_key)."<br>";
                        $img_size = getimagesize($storage->get_path($new_key));
                        echo "Crop.........";
                        $W = $img_size[0];
                        $H = $img_size[1];
                        if($W>=0.75*$H) {
                            $crop_w = 0.75*$H;
                            $crop_x = ($W-$crop_w)/2;
                            $crop_h = $H;
                            $crop_y =0;
                        }
                        else {
                            $crop_w = $W;
                            $crop_x = 0;
                            $crop_h = 1.25*$W;
                            $crop_y =($H-$crop_h)/2;
                        }
                        if($storage->img_crop($storage->get_path($new_key), $crop_key, $crop_x, $crop_y, $crop_w, $crop_h)) {
                            echo "OK<BR>";
                            echo 'path=<b>'.$storage->get_path($crop_key).'</b><br>';
                            echo 'width='.$crop_w."<br>";
                            echo 'height='.$H."<br>";
                        }
                        else {
                            echo "<b>FALSE</b><BR>";
                        }
                    }
                    else {
                        echo "<b><h3>ERROR:</h3>File ".$storage->get_path($old_key).' wasnt copy. UID = '.$user_id.'</b><br>';
                        $errors = true;
                    }
                    if(!$errors) {
                        echo 'Deleting old images.........<br>';
                        $image_types = conf::get('image_types');
                        foreach ($image_types as $type_id => $type_value)
                           if(file_exists($storage->get_path($type_id.'/user/'.  $user_id.$old_salt.'.jpg'))) {
                               unlink ($storage->get_path($type_id.'/user/'.  $user_id.$old_salt.'.jpg'));
                               echo 'Image  <b>'.$storage->get_path($type_id.'/user/'.  $user_id.$old_salt.'.jpg').'</b> was deleted<br>';
                           }
                       if(file_exists($storage->get_path('user/'.  $user_id.$old_salt.'.jpg'))) {
                               unlink ($storage->get_path('user/'.  $user_id.$old_salt.'.jpg'));
                               echo 'Original image  <b>'.$storage->get_path('user/'.$user_id.$old_salt.'.jpg').'</b> was deleted<br>';
                           }
                       user_data_peer::instance()->update(array('photo_salt' => $new_salt, 'user_id' => $user_id));
                     }
                     else {
                         echo "<b><h3>ERROR?</h3></b>Image <b>".$storage->get_path($old_key)."</b>was missing, UID = ".$user_id."<BR>";
//                         db_key::i()->set('last_changed_photo_id='.$user_id,'');
                         continue;
                     }
                }
                echo '-----------------------------------------------------------------------------<br>';

               db_key::i()->set('last_changed_photo_id',$user_id);
            }
            
*/




          
        }
      
}
