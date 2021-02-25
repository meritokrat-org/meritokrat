<?
load::app('modules/zayava/controller');
load::model("user/zayava_termination");
class zayava_list_action extends zayava_controller
{
	public function execute()
	{
                if(!$this->access)
                {
                    throw new public_exception('Недостаточно прав');
                }
								
                load::action_helper('pager');
								switch(request::get("filter")){
									case "termination":
										$this->list = user_zayava_termination_peer::instance()->get_statements();
										$this->set_template("termination_list");
										break;
									
									case "deleted":
										$this->list = user_zayava_peer::instance()->show_deleted_items(
															request::get_int('status'),
															htmlspecialchars(addslashes(request::get('req'))),
															$this->region,
															$this->city
														);
										break;
									
									default:
										$this->list = user_zayava_peer::instance()->get_by_status(
															request::get_int('status'),
															htmlspecialchars(addslashes(request::get('req'))),
															$this->region,
															$this->city
														);
										break;
								}
								$this->count_of_list = count($this->list);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 30);
                $this->list = $this->pager->get_list();
                db_key::i()->set('user_'.session::get_user_id().'_viewzayava_time',time());
                mem_cache::i()->delete('zayava_'.session::get_user_id().'_newz');
	}
}