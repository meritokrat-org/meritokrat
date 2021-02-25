<?

class membership_helper
{
	public static function change_status( $user_id=0,$status=1 )
	{
            if(intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,5,29,1360,3949,5968,11752)))
            {
                
                
                
                /////////////////delete from ppo
                load::model('ppo/ppo');
                load::model('ppo/members');
                load::model('user/zayava');
                
                $user_auth = user_auth_peer::instance()->get_item($user_id);
                
                if($user_auth['status']==20) {
                    $user_ppo = ppo_peer::instance()->get_user_ppo($user_id);
                    if($user_ppo) ppo_members_peer::instance()->remove($user_ppo['id'], $user_id);
                    $zayava_data = user_zayava_peer::instance()->get_user_zayava($user_auth['id']);
                    if($zayava_data) {
                        $inp = array(
                                    'id' => $zayava_data['id'],
                                    'user_id' => session::get_user_id(),
                                    'del_reason' => 'Виключення з партії'
                                    );
                        user_zayava_peer::instance()->to_trash($inp);
                    }
                }
                //////////////////end
                
                
                
                user_auth_peer::instance()->update(array(
                    'id' => $user_id,
                    'status' => $status
                ));

                load::model('user/user_status_log');
                user_status_log_peer::instance()->add(array(
                    'user_id' => $user_id,
                    'status' => $status,
                    'date' => time(),
                    'who' => session::get_user_id()
                ));

                $user_auth = user_auth_peer::instance()->get_item($user_id);
                $user_data = user_data_peer::instance()->get_item($user_id);

                if($user_data['photo_salt']!='')
                {
                    load::system('storage/storage_simple');
                    $storage = new storage_simple($user_id);
                    $new_salt = user_data_peer::instance()->regenerate_photo_salt($user_id);

                    user_helper::photo_watermark(
                        user_helper::change_photo_from_status($storage,$user_id,$user_data,$new_salt),
                        $user_auth['status'],
                        intval($user_auth['expert'])
                    );
                    
                    user_data_peer::instance()->update(array(
                        'user_id' => $user_id,
                        'photo_salt' => $new_salt
                    ));

                    if($redis_data = db_key::i()->get('crop_coord_user_' . $user_id))
                    {
                        $redis_data = explode("-",$redis_data);
                        $user_data = user_data_peer::instance()->get_item($user_id);

                        $key = 'profile/' . $user_id . $new_salt . '.jpg';
                        $_REQUEST['id'] = $user_id;
                        $_REQUEST['height'] = ceil($redis_data[3]);
                        $_REQUEST['width'] = ceil($redis_data[2]);
                        $_REQUEST['xcor'] = ceil($redis_data[0]);
                        $_REQUEST['ycor'] = ceil($redis_data[1]);
                        if(@is_file($storage->get_path($key)))
                        {
                            $image_size = getimagesize($storage->get_path($key));

                            $_REQUEST['img_w'] = 200;
                            $_REQUEST['img_h'] = ceil((200*$image_size[1])/$image_size[0]);

                            user_helper::crop_photo($storage,$user_data);
                        }
                    }
                }

                if($status==20 && !$user_auth['statusmail'])
                {
                    user_auth_peer::instance()->update(array(
                        'id' => $user_id,
                        'statusmail' => 1
                    ));

                    load::action_helper('user_email', false);
                    user_email_helper::send_sys('change_status',$user_id,31);
                    self::add_number($user_id);
                    rating_helper::calculateUserRating($user_id);
                    $inviter = ($user_auth['recomended_by']) ? $user_auth['recomended_by'] : (($user_auth['invited_by']) ? $user_auth['invited_by'] : 0);
                    if($inviter) rating_helper::updateRating($inviter, 'status');

                }
            }
	}

        public static function add_number( $user_id=0 )
	{
            load::model('user/membership');
            $membership = user_membership_peer::instance()->get_user($user_id);

            if($membership['kvnumber'])    #если уже есть номер партбилета
                return;

            load::model('user/zayava');
            $zayava = user_zayava_peer::instance()->get_user_zayava($user_id);
            load::model('user/user_payments');
            $payments = user_payments_peer::instance()->get_user($user_id,1,2);

            if(!$zayava['id'] OR ($zayava['kvitok'] && count($payments)==0))    #если нет заявы или в заяве указан, но не уплачен вступительный взнос
                return;
            
            if($membership['id'])
            {
                user_membership_peer::instance()->update(array(
                    'id' => $membership['id'],
                    'kvnumber' => (user_membership_peer::instance()->get_max_num()+1)
                ));
            }
            else
            {
                user_membership_peer::instance()->insert(array(
                    'user_id' => $user_id,
                    'kvnumber' => (user_membership_peer::instance()->get_max_num()+1)
                ));
            }
	}

        public function calculate_debt( $user_id=0 )
        {
            load::model('user/membership');
            load::model('user/user_payments');

            $membership = user_membership_peer::instance()->get_user($user_id);

            if(!$membership['invdate'])return false;

            $paylog = user_payments_peer::instance()->get_months($membership['user_id']);
            $curdate = mktime(0, 0, 0, date('n'), 1, date('Y'));

            if(date('j',$membership['invdate'])<15)
            {
                $date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']));
            }
            else
            {
                $date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']))+(86400*date('t',$membership['invdate']));
            }

            $debt = 0;

            for($i=$date;$i<=$curdate;$i=$i+(86400*date('t',$i)))
            {
                if(!is_array($paylog) || !in_array($i,$paylog))
                {
                    #плюсуем в долг
                    $debt += 1;
                }
            }

            user_membership_peer::instance()->update(array(
                'id' => $membership['id'],
                'debt' => $debt
            ));
        }
        public static function get_party_off_types($id=false) {
            $types = array(
                            0=>'&mdash;',
                            1=>t('Добровольно'),
                            2=>t('Автоматически'),
                            3=>t('Исключение'),
                            100=>t('Восстановлен')
                            );
            return ($id && isset($types[$id])) ? $types[$id] : $types;
        }

        public static function get_party_off_auto_reason($id=false) {
            $types = array(
                            1=>t('Смерть'),
                            2=>t('Неработоспособность'),
                            3=>t('Решение суда')
                            );
            return ($id && isset($types[$id])) ? $types[$id] : $types;
        }
        
        public static function get_party_off_except_reason($id=false) {
            $types = array(
                            1=>t('Нарушение статута').' (4.10.1)',
                            2=>t('Вред репутации').' (4.10.2)',
                            3=>t('Разглашение внутрипартийной информации').' (4.10.3)',
                            4=>t('Систематическая неуплата взносов').' (4.10.4)',
                            5=>t('Hесоответствие требованиям').' (4.10.5)',
                            6=>t('Членство в другой ПП').' (4.10.6)'
                            );
            return ($id && isset($types[$id])) ? $types[$id] : $types;
        }
}