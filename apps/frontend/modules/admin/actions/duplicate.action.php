<?
load::app('modules/admin/controller');

class admin_duplicate_action extends admin_controller
{
	public function execute()
	{
		load::action_helper('pager');

		$this->list = db::get_rows('SELECT second.user_id as scn, first.user_id as fst
                        FROM user_data first, user_data second
                        WHERE
                        (first.last_name ILIKE second.last_name
                        AND
                        first.first_name ILIKE second.first_name
                        AND
                        first.region_id = second.region_id
                        AND
                        first.user_id > second.user_id
                        AND
                        first.duplicate = 0
                        AND
                        second.duplicate = 0)
                        OR
                        (first.last_name ILIKE second.first_name
                        AND
                        first.first_name ILIKE second.last_name
                        AND
                        first.region_id = second.region_id
                        AND
                        first.user_id > second.user_id
                        AND
                        first.duplicate = 0
                        AND
                        second.duplicate = 0)
                        ORDER BY first.user_id DESC',
			array(),
			null,
			'user_duplicate');
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 30);
		$this->list = $this->pager->get_list();
	}
}