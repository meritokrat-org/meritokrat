<?php

class profile_save_work_action extends frontend_controller
{
	public function execute()
	{
		load::model('geo');
		load::model('user/user_works');
		load::model('user/user_works_categories');
		$this->set_renderer('ajax');

		$__data = [
			"user_id" => request::get_int("user_id"),
			"country_id" => request::get_int("country_id"),
			"region_id" => request::get_int("region_id"),
			"city_id" => request::get_int("city_id"),
			"region_txt" => request::get_string("region_txt", ""),
			"city_txt" => request::get_string("city_txt", ""),
			"category_id" => request::get_int("category_id"),
			"date_start" => request::get("date_start"),
			"date_finish" => request::get("date_finish"),
			"description" => request::get_string("description"),
			"name" => request::get_string("name"),
			"post" => request::get_string("post")
		];

		$this->json["country"] = geo_peer::instance()->get_country($__data["country_id"])["name_".translate::get_lang()];
		$this->json["region"] = geo_peer::instance()->get_region($__data["region_id"])["name_".translate::get_lang()];

		if($__data["region_id"] > 28)
			$this->json["city"] = geo_peer::instance()->get_city($__data["city_id"], true)["name_".translate::get_lang()];
		else
			$this->json["city"] = geo_peer::instance()->get_city($__data["city_id"])["name_".translate::get_lang()];

		$this->json["category"] = db::get_row("SELECT name_".translate::get_lang()." FROM user_works_categories WHERE id = ".$__data["category_id"])["name_".translate::get_lang()];

		if(request::get_int("id", 0) > 0)
		{
			$this->json["id"] = request::get_int("id");
			user_works_peer::instance()->update(array_merge(["id" => request::get_int("id")], $__data));
		}
		else
			$this->json["id"] = user_works_peer::instance()->insert($__data);
	}
}

?>
