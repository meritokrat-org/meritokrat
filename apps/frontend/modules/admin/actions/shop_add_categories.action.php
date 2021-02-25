<?

load::app('modules/admin/controller');

class admin_shop_add_categories_action extends admin_controller
{
	public function execute()
	{
		load::model('shop/shop_categories');

		$id = request::get_int("id");

		// GET DATA
		if(request::get_bool("submit", false))
		{
			$data = [
				"title_ua" => request::get_string("title_ua"),
				"title_ru" => request::get_string("title_ru"),
				"is_public" => request::get_bool("is_public")
			];
		}

		// EDITING
		if(request::get_bool("edit", false))
			$this->data = shop_categories_peer::instance()->get_item(request::get_int("id"));

		// SAVE
		if(request::get_bool("submit", false) && request::get_int("id"))
			shop_categories_peer::instance()->update(array_merge(["id" => request::get_int("id")], $data));
		elseif(request::get_bool("submit", false) && ! request::get_int("id"))
			$id = shop_categories_peer::instance()->insert($data);

		// PHOTO
		load::system('storage/storage_simple');
		load::view_helper('image');
		load::form('shop/shop_item_picture');
		$form = new shop_item_picture_form();
		$form->load_from_request();

		if (request::get_file('photo')["name"] != "") {

			$storage = new storage_simple();
			$salt = shop_categories_peer::instance()->generate_photo_salt($id);
			$key = 'shop/category/'.$id.$salt.'.jpg';

			$storage->save_uploaded($key, request::get_file('photo'));
		}

		if(request::get_bool("submit", false))
			$this->redirect("/admin/shop_categories");
	}
}
