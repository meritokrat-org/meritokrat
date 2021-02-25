<?

class context
{
	protected static $registry = array();
	
	public static function set( $name, $value )
	{
		self::$registry[$name] = $value;
	}
	
	public static function get( $name )
	{
		return self::$registry[$name];
	}
	
	public static function set_app( $app_name )
	{
		self::set('app_name', $app_name);
	}
	
	public static function get_app()
	{
		return self::get('app_name');
	}
	
	public static function set_controller( $controller )
	{
		self::set( 'controller', $controller );
	}
	
	/**
	 * @return basic_controller
	 */
	public static function get_controller()
	{
		return self::get('controller');
	}
}