<?

class email_validator extends abstract_validator
{
	protected $error = 'Неправильный email адресс';

	public function is_valid( $value )
	{
		return preg_match('/[^\@]\@.+\.[a-z]+/', $value);
	}
}