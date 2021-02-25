<?

load::system('form/filters/abstract_filter');

class filters_factory
{
	public static function get($name)
	{
		$class = $name . '_filter';
		load::system('form/filters/' . $class);

		return new $class;
	}
}