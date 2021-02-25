<?

class profile_payment_delete_action extends frontend_controller
{

    public function execute()
    {
        $this->set_renderer('ajax');
        $this->json = array();

        load::model('user/user_payments');
        load::model('user/user_payments_log');

        if(!$pid = request::get_int('id'))
            die("1");

        $payment = user_payments_peer::instance()->get_item($pid);

        user_payments_peer::instance()->update(array(
            'id' => $pid,
            'del' => 1
        ));
        user_payments_log_peer::instance()->insert(array(
            'payment_id' => $pid,
            'type' => 3,
            'who' => session::get_user_id(),
            'date' => time(),
            'payment' => serialize($payment)
        ));

        load::action_helper('membership',false);
        membership_helper::calculate_debt($payment['user_id']);
    }

}