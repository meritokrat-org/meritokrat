<?

load::system('form/validators/abstract_validator');

class validators_factory
{
	public static function get($name)
	{
		$class = $name . '_validator';

		if ( !class_exists($class) )
		{
			load::system('form/validators/' . $class);
		}

		return new $class;
	}
}