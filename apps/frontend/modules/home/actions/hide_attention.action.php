<?
class home_hide_attention_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
            $attention_id=request::get_int('attention_id');
            
            //считаем до трех...
            if (!db_key::i()->exists('attention_hides:'.$attention_id.':'.session::get_user_id()))
            {
                echo 1;
            db_key::i()->set('attention_hides:'.$attention_id.':'.session::get_user_id(), 1);
            }
            else 
            {
                $attention_hides=intval(db_key::i()->get('attention_hides:'.$attention_id.':'.session::get_user_id()))+1;
                
                echo '-'.$attention_hides;
                db_key::i()->set('attention_hides:'.$attention_id.':'.session::get_user_id(),  $attention_hides);
            }
            
            // фиксируем отказ смотреть сегодня
            db_key::i()->set('attention:'.date("md").':'.session::get_user_id(), true);
            
            die();
        }
}
