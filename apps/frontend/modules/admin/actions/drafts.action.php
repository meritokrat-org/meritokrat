<?

load::app('modules/admin/controller');
class admin_drafts_action extends admin_controller
{
	public function execute()
	{
              load::model('drafts');
              if(trim(request::get_string('draftname'))!='')
                {
            drafts_peer::instance()->add_draft(trim(request::get_string('draftname')), trim(request::get_string('draftbody')));
                }elseif(request::get_int('draft_id')>0)
                {
                $data=drafts_peer::instance()->get_item(request::get_int('draft_id'));
                die(stripcslashes($data['text']));
                }else die('error');
                die();
        }
}
