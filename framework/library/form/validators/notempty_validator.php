<?

class notempty_validator extends abstract_validator
{
	protected $error = 'Заполните это поле';

	public function is_valid( $value )
	{
		return (bool)$value;
	}
}