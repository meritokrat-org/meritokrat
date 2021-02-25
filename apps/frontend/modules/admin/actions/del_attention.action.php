<?

load::app('modules/admin/controller');
class admin_del_attention_action extends admin_controller
{
	public function execute()
	{
		if ($id=request::get('del'))
                {
                    if (db::exec("DELETE FROM attentions WHERE id=".$id)) die('1');
                }
                die('error');
	}
}
