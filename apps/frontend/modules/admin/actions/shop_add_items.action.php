<?

load::app('modules/admin/controller');

class admin_shop_add_items_action extends admin_controller
{
	public function execute()
	{
		load::model('shop/shop_categories');
		load::model('shop/shop_items');

		$id = request::get_int("id");

		// GET DATA
		if(request::get_bool("submit", false))
		{
			$data = [
				"name_ua" => request::get_string("name_ua"),
				"name_ru" => request::get_string("name_ru"),
				"category_id" => request::get_int("category_id"),
				"is_public" => request::get_bool("is_public"),
				"price" => request::get_int("price"),
				"description_ru" => request::get_string("description_ru"),
				"description_ua" => request::get_string("description_ua"),
				"size" => serialize(request::get("size")),
				"priority" => request::get_int("priority")
			];
		}

		$this->categories = array();
		foreach(shop_categories_peer::instance()->get_list(["is_public" => "true"]) as $category)
			$this->categories[] = shop_categories_peer::instance()->get_item($category);

		// EDITING
		if(request::get_bool("edit", false))
			$this->data = shop_items_peer::instance()->get_item($id);

		if(
			(strlen($this->data["size"]) > 0)
			&& ( ! is_null($this->data["size"]))
			&& ( count(unserialize($this->data["size"])) > 0)
		)
			$this->data["size"] = unserialize($this->data["size"]);
		else
			$this->data["size"] = array();

		// SAVE
		if(request::get_bool("submit", false) && request::get_int("id"))
			shop_items_peer::instance()->update(array_merge(["id" => request::get_int("id")], $data));
		elseif(request::get_bool("submit", false) && ! request::get_int("id"))
			$id = shop_items_peer::instance()->insert($data);

		// PHOTO
		load::system('storage/storage_simple');
		load::view_helper('image');
		load::form('shop/shop_item_picture');
		$form = new shop_item_picture_form();
		$form->load_from_request();

		if (request::get_file('photo')["name"] != "") {
			$storage = new storage_simple();
			$salt = shop_items_peer::instance()->generate_photo_salt($id);
			$key = 'shop/item/'.$id.$salt.'.jpg';

			$storage->save_uploaded($key, request::get_file('photo'));
		}

		if(request::get_bool("submit", false))
			$this->redirect("/admin/shop_items");
	}
}
