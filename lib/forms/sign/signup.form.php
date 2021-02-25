<?

load::system('form/form');

class signup_form extends form
{
	public function set_up()
	{
		$this->add_element('phone');
		$this->add_element('email');
		$this->add_element('password');
		$this->add_element('first_name');
		$this->add_element('last_name');

		$this->add_validator('email', 'email_unique');

		$this->add_filter('email', 'lower_case');
		$this->add_filter('email', 'trim');
	}
}

class email_unique_validator extends abstract_validator
{
	protected $error = 'Цей email вже зареєстрований або некоректний';

	public function is_valid( $value )
	{
		load::model('user/user_auth');
                $valid_email = filter_var($value,FILTER_VALIDATE_EMAIL);
		return ($valid_email&&(!user_auth_peer::instance()->get_by_email($value)));
	}
}