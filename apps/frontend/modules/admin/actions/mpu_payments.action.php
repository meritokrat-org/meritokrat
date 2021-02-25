<?
load::app('modules/admin/controller');
class admin_mpu_payments_action extends admin_controller
{

	public function execute()
	{
        
                load::model('mpu_payments');
                
                load::action_helper('pager', true);
                
                if (request::get('submit'))
                {
                    $this->added_payment=mpu_payments_peer::instance()->insert(array(
                        'date' => request::get_string('pay_date'),
                        'payment_ts' => strtotime(request::get_string('pay_date')),
                        'amount' => request::get_int('pay_sum'),
                        'way' => request::get_string('pay_system'),
                        'fio' => request::get_string('pay_fio'),
                        'email' => request::get_string('pay_email'),
                        'status' => 'success',
                    ));
                }
                
                request::get_string('order') ? $order=request::get_string('order') : $order='id';
                request::get_string('sc') ? $sc=' '.request::get_string('sc') : $sc=' DESC';
                
                request::get_string('status') ? $status=request::get_string('status') : $status='success';
                
                if (request::get('way')) $filters['way']=request::get('way');
                if (request::get('site')) $filters['site']=request::get('site');
                if (request::get_string('period_begin')) $filters['period_begin']=request::get_string('period_begin');
                if (request::get_string('period_end')) $filters['period_end']=request::get_string('period_end');
                
                if (request::get_string('amount_start')) $filters['amount_start']=request::get_string('amount_start');
                if (request::get_string('amount_end')) $filters['amount_end']=request::get_string('amount_end');
                $this->get_ways=(array)$filters['way'];
                $this->get_sites=(array)$filters['site'];
                $this->list=mpu_payments_peer::instance()->search($filters, $order.$sc,$status);
                
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 30);
		$this->list = $this->pager->get_list();
                
        }
}
?>
