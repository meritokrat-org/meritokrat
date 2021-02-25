<?php

abstract class shop_controller extends frontend_controller
{
	public function init()
	{
		parent::init();

		load::model('shop/shop_categories');
		load::model('shop/shop_items');
	}
}