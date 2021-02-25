<?
class vnesky_pay_action extends frontend_controller
{
        protected $authorized_access = true;
        public $commissions = array(
            'bank' => 1,
            'liqpay' => 1,
            'privat' => 1,
            'interkassa' => 3
        );
        public $ways = array(
            'liqpay' => 'LiqPay',
            'privat' => 'Privat24',
            'interkassa' => 'Interkassa'
        );

        public function execute() {
            $this->way=request::get_string('way','liqpay');
            $this->way_title=$this->ways[$this->way];
            $this->commission=$this->commissions[$this->way];
            
            $this->vnesok=db::get_scalar("SELECT vnesok FROM user_zayava WHERE user_id=".session::get_user_id()." LIMIT 1");
            $this->is_opened=db::get_scalar("SELECT approve FROM user_payments WHERE user_id=".session::get_user_id()." AND type=1 LIMIT 1");

        }
}