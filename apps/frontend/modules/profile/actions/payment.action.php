<?

class profile_payment_action extends frontend_controller
{

    public function execute()
    {
        $this->set_layout('');

        load::model('user/user_payments');
        load::model('user/user_payments_log');

        if(!$pid = request::get_int('id'))
            die("1");

        $payment = user_payments_peer::instance()->get_item($pid);

        if(!request::get_int('approve'))
        {
            if(!request::get_int('summ') || !request::get('date'))
                die("1");
            user_payments_peer::instance()->update(array(
                'id' => $pid,
                'summ' => request::get_int('summ'),
                'method' => request::get_int('method'),
                'way' => request::get_int('way'),
                'period' => $this->get_timestamp(request::get_int('month'),request::get_int('year')),
                'text'=> strip_tags(request::get_string('text')),
                'date' => $this->get_timestamp(request::get('date'))
            ));
            user_payments_log_peer::instance()->insert(array(
                'payment_id' => $pid,
                'type' => 1,
                'who' => session::get_user_id(),
                'date' => time(),
                'payment' => serialize($payment)
            ));
        }
        else
        {
            $approve = 1;
            if(session::has_credential('admin'))
            {
                $approve = 2;
            }
            user_payments_peer::instance()->update(array(
                'id' => $pid,
                'approve' => $approve
            ));
            user_payments_log_peer::instance()->insert(array(
                'payment_id' => $pid,
                'type' => 2,
                'who' => session::get_user_id(),
                'date' => time()
            ));
            if(session::has_credential('admin'))
            {
                load::action_helper('membership',false);
                membership_helper::calculate_debt($payment['user_id']);
            }
            switch($payment['type']) {
                case '3': $update_rating_action = 'charitable'; break;
                case '2': $update_rating_action = 'regular'; break;
                case '1': $update_rating_action = 'membership'; break;
                default : break;
            }
            if($update_rating_action) rating_helper::updateRating($payment['user_id'], $update_rating_action);
        }

        if(session::has_credential('admin') && $payment['type']==1)
        {
            load::action_helper('membership', false);
            membership_helper::add_number($payment['user_id']);
        }

        $this->p = user_payments_peer::instance()->get_item($pid);

        $this->has_access = request::get_int('has_access');
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

}