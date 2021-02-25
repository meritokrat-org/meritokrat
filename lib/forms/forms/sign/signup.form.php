<?

load::system('form/form');

class signup_form extends form
{
	public function set_up()
	{
		$this->add_element('email');
		$this->add_element('password');
		$this->add_element('type');

		$this->add_validator('email', 'email_unique');

		$this->add_filter('email', 'lower_case');
		$this->add_filter('email', 'trim');
	}
}

class email_unique_validator extends abstract_validator
{
	protected $error = 'Этот email уже зарегистрирован';

	public function is_valid( $value )
	{
		load::model('user/user_auth');
		return !user_auth_peer::instance()->get_by_email($value);
	}
}