<?

load::app('modules/admin/controller');
class admin_complaint_action extends admin_controller
{
	public function execute()
	{
                load::model('admin_complaint');

		if(request::get_int('moderator_id'))
                {
                    $this->htitle = t('Жалобы на модератора').' '.strip_tags(user_helper::full_name(request::get_int('moderator_id')),'<a>');
                    $this->list = admin_complaint_peer::instance()->get_list(array('moderator_id'=>request::get_int('moderator_id')));
                }
                elseif(request::get_int('user_id'))
                {
                    $this->htitle = t('Жалобы участника').' '.strip_tags(user_helper::full_name(request::get_int('user_id')),'<a>');
                    $this->list = admin_complaint_peer::instance()->get_list(array('user_id'=>request::get_int('user_id')));
                }
                else
                {
                    $this->htitle = t('Жалобы');
                    $this->list = admin_complaint_peer::instance()->get_list();
                }
                load::action_helper('pager', true);
                $this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 15);
		$this->list = $this->pager->get_list();
	}
}
