<?

class profile_payment_check_action extends frontend_controller
{

    public function execute()
    {
        $this->set_renderer('ajax');

        load::model('user/user_payments');

        $id = request::get_int('id');
        $user_id = request::get_int('user_id');
        $type = request::get_int('type');
        $period = $this->get_timestamp(request::get_int('month'),request::get_int('year'));

        if($type!=2)die();

        if($id)
        {
            $payment = user_payments_peer::instance()->get_item($id);
            if(db::get_scalar("SELECT COUNT(*) FROM user_payments WHERE id != $id AND user_id = ".$payment['user_id']." AND type = $type AND period = $period  AND del = 0"))
                die('error');
        }
        else
        {
            if(db::get_scalar("SELECT COUNT(*) FROM user_payments WHERE user_id = $user_id AND type = $type AND period = $period AND del = 0"))
                die('error');
        }
        die();
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