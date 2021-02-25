<?

class profile_delete_recomend_action extends frontend_controller
{
	public function execute()
	{
            load::model('user/user_recommend');
            load::model('user/user_recommend_log');
            load::view_helper('status');
            load::model('user/user_data');
            load::model('user/user_auth');

            $rec_data = user_recommend_peer::instance()->get_item(request::get('id'));

            if (db::get_scalar('SELECT id
                FROM user_recommend
                WHERE recommending_user_id='.session::get_user_id().'
                    AND id='.request::get('id').' AND status=20')){

                user_recommend_peer::instance()->delete_item(request::get('id'));
                if($rec_data['id'])
                {
                    user_recommend_log_peer::instance()->insert(array(
                        'user_id' => $rec_data['user_id'],
                        'data' => serialize($rec_data),
                        'ts' => time()
                    ));
                }
            }
            elseif ($user_recommend_id=db::get_scalar('SELECT user_id
                FROM user_recommend
                WHERE recommending_user_id='.session::get_user_id().'
                AND id='.request::get('id').' AND status=10')){

                user_recommend_peer::instance()->delete_item(request::get('id'));
                db_key::i()->delete(request::get('id')."_ask_".status_helper::MERITOCRAT."_recommendation_".session::get_user_id());
                if($rec_data['id'])
                {
                    user_recommend_log_peer::instance()->insert(array(
                        'user_id' => $rec_data['user_id'],
                        'data' => serialize($rec_data),
                        'ts' => time()
                    ));
                }

                $user_recommend=user_auth_peer::instance()->get_item($user_recommend_id);
                if ($user_recommend['status']==10)
                {
                       user_auth_peer::instance()->update(array("id"=>request::get('id'),"status"=>5));
                       $rec_count=db::get_scalar('SELECT count(*) as count
                                FROM user_recommend
                                WHERE user_id='.request::get('id').' AND status=10');

                       if($rec_count==0){
                            if($user_data['photo_salt']!=''){
                            $user_data=user_data_peer::instance()->get_item(request::get('id'));
                            $this->user=user_auth_peer::instance()->get_item(request::get('id'));
                            $uid = $this->user['id'];
                            load::system('storage/storage_simple');
                            $storage = new storage_simple($uid);

                            $new_salt = user_data_peer::instance()->regenerate_photo_salt($uid);
                            user_helper::photo_watermark(
                                    user_helper::change_photo_from_status($storage,$uid,$user_data,$new_salt),
                                    5, $this->user['expert']);
                            user_data_peer::instance()->update(array(
                                'user_id' => $uid, 'photo_salt' => $new_salt));

                            $redis_data = db_key::i()->get('crop_coord_user_' . $uid);
                            $redis_data = explode("-",$redis_data);
                            $key = 'profile/' . $uid . $new_salt . '.jpg';
                            $crop_key = 'user/' . $uid . $new_salt . '.jpg';


                            load::system('storage/storage_simple');
                            $storage = new storage_simple();
                            $user_data = user_data_peer::instance()->get_item($uid);
                            $_REQUEST['id']=$uid;
                            $_REQUEST['height']=$redis_data[3];
                            $_REQUEST['width']=$redis_data[2];
                            $_REQUEST['xcor']=$redis_data[0];
                            $_REQUEST['ycor']=$redis_data[1];
                            $image_size = getimagesize($storage->get_path($key));

                            $_REQUEST['img_w']=200;
                            $_REQUEST['img_h']=round((200*$image_size[1])/$image_size[0]);

                            user_helper::crop_photo($storage,$user_data);
                        }
                    }
                }
            }
            $this->redirect($_SERVER['HTTP_REFERER']);
	}
}
