<?

class admin_cron_user_debt_action extends basic_controller
{
        
        public function execute()
        {
            $this->disable_layout();
            $this->set_renderer(false);

            load::model('user/user_payments');
            load::model('user/membership');
            load::model('user/zayava');
            load::view_helper('user');
            load::action_helper('user_email',false);
            
            $query = user_membership_peer::instance()->get_members();
            foreach($query as $id)
            {
                $membership = user_membership_peer::instance()->get_item($id);
                $payments = user_payments_peer::instance()->get_total($membership['user_id']);
                $paylog = user_payments_peer::instance()->get_months($membership['user_id']);
                $zayava = user_zayava_peer::instance()->get_user_zayava($membership['user_id']);
                $curdate = mktime(0, 0, 0, date('n'), 1, date('Y'));

                #5е или 15е число месяца - проверяем долги и рассылаем корреспонденцию
                if(date('j')==5 OR date('j')==15)
                {
                    #ВСТУПИТЕЛЬНЫЙ ВЗНОС

                    if($zayava['kvitok'] && !$payments[1])
                    {
                        $options = array(
                            "%fullname%" => user_helper::full_name($membership['user_id'],false,array(),false)
                            );
                        user_email_helper::send_sys('user_vnesok_debt',$membership['user_id'],null,$options);
                    }

                    #ЕЖЕМЕСЯЧНЫЙ ВЗНОС

                    #если зарегался до 15 числа то платит взнос за тот месяц, если позже то считаем за след месяц
                    if(date('j',$membership['invdate'])<15)
                    {
                        $date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']));
                    }
                    else
                    {
                        $date = $this->nextmonth($membership['invdate']);
                        //$date = mktime(0, 0, 0, date('n',$membership['invdate']), 1, date('Y',$membership['invdate']))+(86400*date('t',$membership['invdate']));
                    }

                    $debt = 0;
                    $period = array();
                    //$step = (86400*date('t',$date)); #сделал так как щас в рабочем
                    //for($i=$date;$i<=$curdate;$i=$i+$step)
                    for($i=$date;$i<=$curdate;$i=$this->nextmonth($i))
                    {
                        if(!is_array($paylog) || !in_array($i,$paylog))
                        {
                            #плюсуем в долг
                            $debt += 1;
                            $period[] = user_helper::get_months(date('n',$i)).' '.date('Y',$i);
                        }
                        //if(date('m',$i)==10)
                          //  $step=(90000*date('t',$i));
                        //else
                          //  $step=(86400*date('t',$i));
                    }
                    if($debt)
                    {
                        $options = array(
                            "%fullname%" => user_helper::full_name($membership['user_id'],false,array(),false),
                            "%date%" => implode(', ',$period)
                        );
                        user_email_helper::send_sys('user_month_debt',$membership['user_id'],null,$options);

                        user_membership_peer::instance()->update(array(
                            'id' => $id,
                            'debt' => $debt
                        ));
                    }
                    unset($period);

                }
            }
        }

        public function nextmonth($data)
        {
            $next = date('n',$data)+1;
            $year = date('Y',$data);
            if($next>12)
            {
                $next=1;
                $year+=1;
            }
            return mktime(0, 0, 0, $next, 1, $year);
        }
  
}
