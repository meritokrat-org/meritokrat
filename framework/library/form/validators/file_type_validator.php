<?

class file_type_validator extends abstract_validator
{
	protected $error = 'Неверный тип файла';
	protected $types = array();

	public function set_types( $types )
	{
		$this->types = $types;
	}

	public function is_valid( $value )
	{
		$extention = strtolower(pathinfo($value['name'], PATHINFO_EXTENSION));
		return in_array($extention, $this->types);
	}
}