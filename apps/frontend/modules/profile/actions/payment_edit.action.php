<?

class profile_payment_edit_action extends frontend_controller
{

    public function execute()
    {
        $this->set_layout('');

        load::model('user/user_payments');

        if($pid = request::get_int('id'))
        {
            if(!request::get_int('summ') || !request::get('date'))
                die("1");
            user_payments_peer::instance()->update(array(
                'id' => $pid,
                'summ' => request::get_int('summ'),
                'method' => request::get_int('method',1),
                'way' => request::get_int('way',1),
                'period' => $this->get_timestamp(request::get_int('month'),request::get_int('year')),
                'text'=> strip_tags(request::get_string('text')),
                'date' => $this->get_timestamp(request::get('date'))
            ));
            if(session::has_credential('admin'))
            {
                $this->admin_act($pid);
            }
        }
        else
        {
            if(!request::get_int('summ') || !request::get('date') || !request::get_int('method'))
                die("1");
            $pid = user_payments_peer::instance()->insert(array(
                'user_id' => request::get_int('user_id'),
                'type' => request::get_int('type'),
                'summ' => request::get_int('summ'),
                'method' => request::get_int('method',1),
                'way' => request::get_int('way',1),
                'text'=> strip_tags(request::get_string('text')),
                'period' => $this->get_timestamp(request::get_int('month'),request::get_int('year')),
                'date' => $this->get_timestamp(request::get('date'))
            ));
            if(session::has_credential('admin'))
            {
                $this->admin_act($pid);
            }
            elseif(request::get_int('method')==1 && request::get_int('way')==1)
            {
                load::model('user/user_desktop');
                $usr = user_data_peer::instance()->get_item(request::get_int('user_id'));
                if(intval($usr['region_id']))
                    $coordinators = user_desktop_peer::instance()->get_regional_coordinators($usr['region_id']);

                if(is_array($coordinators) && count($coordinators)>0)
                {
                    load::view_helper('user');
                    load::action_helper('user_email',false);
                    $vneski = array(1=>t('вступительный'),2=>t('членский'),3=>t('благотворительный'));

                    foreach($coordinators as $receiver)
                    {
                        $options = array(
                            "%receiver%" => user_helper::full_name($receiver,false,array(),false),
                            "%sender%" => user_helper::full_name(request::get_int('user_id'),false,array(),false),
                            "%vnesok%" => $vneski[request::get_int('type')],
                            "%link%" => 'http://'.context::get('server').'/profile/desktop?id='.request::get_int('user_id').'&tab=payments'
                            );
                        user_email_helper::send_sys('vnesok_to_ppo',request::get_int('user_id'),null,$options);
                    }
                }
            }
        }

        $this->list = user_payments_peer::instance()->get_user(request::get_int('user_id'),request::get_int('type'));
    }

    private function get_timestamp($data,$year=false)
    {
        if($year===false)
        {
            $segments = explode('-',$data);
            return mktime(0, 0, 0, $segments[1], $segments[0], $segments[2]);
        }
        else
        {
            return mktime(0, 0, 0, $data, 1, $year);
        }
    }

    private function admin_act($payment_id)
    {
        load::model('user/user_payments_log');
        user_payments_peer::instance()->update(array(
            'id' => $payment_id,
            'approve' => 2
        ));
        user_payments_log_peer::instance()->insert(array(
            'payment_id' => $payment_id,
            'type' => 2,
            'who' => session::get_user_id(),
            'date' => time()
        ));
        $payment = user_payments_peer::instance()->get_item($payment_id);

        switch($payment['type']) {
                case '3': $update_rating_action = 'charitable'; break;
                case '2': $update_rating_action = 'regular'; break;
                case '1': $update_rating_action = 'membership'; break;
                default : break;
            }
            if($update_rating_action) rating_helper::updateRating($payment['user_id'], $update_rating_action);
        
        load::action_helper('membership',false);
        membership_helper::calculate_debt($payment['user_id']);

        if($payment['type']==1)
        {
            membership_helper::add_number($payment['user_id']);
        }
    }

}