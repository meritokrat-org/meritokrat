<?

class conf
{
	protected static $registry = array();
	
	public static function set( $name, $value )
	{
		self::$registry[$name] = $value;
	}
	
	public static function get( $name, $default = null )
	{
		return isset(self::$registry[$name]) ? self::$registry[$name] : $default;
	}
	
	public static function set_from_array( $data )
	{
		foreach ( $data as $name => $value )
		{
			self::set( $name, $value );
		}
	}
}