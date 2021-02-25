<?php

abstract class admin_controller extends frontend_controller
{
	protected $authorized_access = true;
	protected $credentials = array('admin');

}
