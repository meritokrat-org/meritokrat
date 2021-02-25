<?
load::app('modules/zayava/controller');
class zayava_index_action extends zayava_controller
{
	protected $authorized_access = true;

	public function execute()
	{

                $this->auth = user_auth_peer::instance()->get_item(session::get_user_id());
                $this->user = user_data_peer::instance()->get_item(session::get_user_id());

                if(!request::get_int('id'))
                {
                    $this->needed = array('photo_salt'=>t('фото'),'birthday'=>'день народження','segment'=>'сфера дiяльностi','father_name'=>'по батьковi','phone'=>'телефон','location'=>'населений пункт');
                    foreach($this->needed as $k=>$v)
                    {
                        if($k!='phone')
                        {
                            if(!$this->user[$k])
                            {
                                $this->set_template('error');
                                break;
                            }
                        }
                        else
                        {
                            if(!$this->user['phone'] && !$this->user['mobile'] && !$this->user['home_phone'])
                            {
                                $this->set_template('error');
                                break;
                            }
                        }
                    }
                }

                //              оригинал заявления
                if(request::get_int('has_real') && session::get_user_id()==3949) {
                    $val = (in_array(request::get_int('has_real'),array(1,2))) ? (request::get_int('has_real')-1) : 0;
                    $uid = request::get_int('id');
                    $zayava = user_zayava_peer::instance()->get_user_zayava($uid);
                    if($zayava) {
                        $zayava['real_app'] = $val;
                        user_zayava_peer::instance()->update($zayava);
                        die(json_encode(array('success'=>1,'app'=>(request::get_int('has_real')-1) )));
                    }
                    else die(json_encode(array('success'=>0,'debug'=>array($uid,$zayava,$val) ))); 
                    
                    
                }
                
                if(!(request::get_int('zayava') && request::get_int('print')))
                {
                    if(request::get('submit'))
                    {
                        $array = array(
                            'user_id'=>session::get_user_id(),
                            'firstname'=>mb_substr(request::get_string('firstname'), 0, 250),
                            'lastname'=>mb_substr(request::get_string('lastname'), 0, 250),
                            'fathername'=>mb_substr(request::get_string('fathername'), 0, 250),
                            'birthday'=>mb_substr(request::get_string('birthday'), 0, 250),
                            'citizenship'=>mb_substr(request::get_string('citizenship'), 0, 250),
                            'phone'=>mb_substr(request::get_string('phone'), 0, 250),
                            'email'=>mb_substr(request::get_string('email'), 0, 250),
                            'country'=>request::get_int('country'),
                            'region'=>request::get_int('region'),
                            'city'=>request::get_int('city'),
                            'location'=>mb_substr(request::get_string('location'), 0, 250),
                            'postindex'=>mb_substr(request::get_string('postindex'), 0, 250),
                            'street'=>mb_substr(request::get_string('street'), 0, 250),
                            'building'=>mb_substr(request::get_string('building'), 0, 250),
                            'flat'=>mb_substr(request::get_string('flat'), 0, 250),
                            'kvitok'=>request::get_int('vstupvnesok'),
                            'vnesok'=>request::get_int('monthvnesok'),
                            'avnesok'=>request::get_int('avnesok'),
                            'bvnesok'=>request::get_int('dobrovnesok'),
                            'date'=>time(),
                            'ppo'=>request::get_int('ppoval'),
                            'ppotype'=>request::get_int('ppotype'),
                            'warning'=>request::get_int('warning'),
                            'segment'=>request::get_int('segment')
                        );
                        if(request::get_int('zayava_id') && session::has_credential('admin'))
                        {
                            $array['id'] = request::get_int('zayava_id'); #апдейт существующей заявы
                            unset($array['user_id']);
                            user_zayava_peer::instance()->update($array);
                            if(request::get_int('list'))
                                $this->redirect('/zayava/list');
                            else
                                $this->redirect('/zayava');
                        }
                        else
                        {
                            if(request::get_int('new') || user_zayava_peer::instance()->get_user(session::get_user_id()))   #создание оффлайн анкеты
                            {
                                $this->offline = $this->create_offline($array);
                                $array['user_id'] = $this->offline;
                            }

                            $id = user_zayava_peer::instance()->insert($array);

                            if($this->offline)
                                $this->redirect('/zayava&offline='.$this->offline);
                            else
                                $this->redirect('/zayava');
                        }
                    }
                    else
                    {
                        if(request::get_int('id'))
                        {
                            $this->item = user_zayava_peer::instance()->get_item(request::get_int('id'));
                            $this->check_access($this->item['user_id']);
                        }
                        $this->recommend = user_recommend_peer::instance()->check_recommend(session::get_user_id());
                        $this->zayava = user_zayava_peer::instance()->get_user(session::get_user_id());
                        $this->zdata = user_zayava_peer::instance()->get_user_zayava(session::get_user_id());
                    }
                }
                else
                {
                    $this->set_layout('');
                    $this->set_template('print');

                    $this->item = user_zayava_peer::instance()->get_item(request::get_int('zayava'));
                    $this->check_access($this->item['user_id']);
                    $this->recommend = user_recommend_peer::instance()->get_recommenders($this->item['user_id'],20);
                }
	}

        private function check_access( $user=0 )
        {
            if($this->access=='all' OR $this->auth['status']==20)
            {
                return true;
            }
            elseif($user && $user==session::get_user_id())
            {
                return true;
            }
            elseif($this->access=='region' && in_array($this->user['region_id'],$this->region))
            {
                return true;
            }
            elseif($this->access=='city' && in_array($this->user['city_id'],$this->city))
            {
                return true;
            }
            else
            {
                throw new public_exception('Недостаточно прав');
            }
        }

        private function create_offline( $array )
	{
                $arr = db::get_cols("SELECT id FROM user_auth WHERE offline = ".session::get_user_id());
                $umail = 'offline_'.session::get_user_id().'_'.time();
                $id = user_auth_peer::instance()->insert($umail,'offline',1,false,0,0,0,0,0,session::get_user_id());
                $user = user_auth_peer::instance()->get_item($id);

                user_data_peer::instance()->insert(array(
                        'user_id' => $user['id'],
                        'first_name' => $array['firstname'],
                        'last_name' => $array['lastname'],
                        'father_name' => $array['fathername'],
                        'birthday' => $array['birthday'],
                        'city_id' => $array['city'],
                        'region_id' => $array['region'],
                        'country_id' => $array['country'],
                        'location'=> $array['location'],
                        'phone'=> $array['phone'],
                        'citizenship' => $array['citizenship'],
                        'postindex'=> $array['postindex'],
                        'street'=> $array['street'],
                        'house'=> $array['building'],
                        'flat'=> $array['flat'],
                        'segment'=>request::get_int('segment')
                ));

                load::model('user/user_bio');
                user_bio_peer::instance()->insert(array(
                        'user_id' => $user['id']
                ));
                load::model('user/user_work');
                user_work_peer::instance()->insert(array(
                        'user_id' => $user['id']
                ));
                load::model('user/user_education');
                user_education_peer::instance()->insert(array(
                        'user_id' => $user['id']
                ));
                load::model('user/user_desktop');
                user_desktop_peer::instance()->insert(array(
                        'user_id' => $user['id']
                ));

                return $user['id'];
	}
}