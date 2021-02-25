<?

class logger
{
	const LEVEL_NORMAL = 1;
	const LEVEL_WARNING = 2;
	const LEVEL_CRITICAL = 3;
	
	protected static $instance = null;
	protected static $transactions = array();
	
	protected $data = array();
	
	protected function __construct()
	{
		
	}
	
	public function add_message( $message, $tag = null, $level = null, $details = array() )
	{
		if ( !$tag )
		{
			$tag = 'system';
		}
		
		if ( !$level )
		{
			$level = self::LEVEL_NORMAL;
		}
		
		$details['message'] = $message;
		$details['level'] = $level;
		
		$this->data[$tag][] = $details;
	}
	
	public function get_messages( $tag = null )
	{
		if ( $tag )
		{
			return $this->data[$tag];
		}
		
		return $this->data;
	}
	
	public function write()
	{
		
	}
	
	/**
	 * @return logger
	 */
	public static function instance()
	{
		if ( self::$instance === null )
		{
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	
	public static function add($message, $tag = null, $level = null, $details = array())
	{
		self::instance()->add_message( $message, $tag, $level, $details );
	}
	
	public static function get( $tag = null )
	{
		return self::instance()->get_messages( $tag );
	}
	
	/**
	 * Used for time tracking while logging
	 * Returns transaction identifier
	 * see commit() method
	 * 
	 * @return int
	 */
	public static function start( $message, $tag = null, $level = null, $details = array() )
	{
		self::$transactions[] = array($message, $tag, $level, $details, microtime(true));
		
		return count(self::$transactions) - 1;
	}
	
	/**
	 * Transaction finishing
	 */
	public static function commit( $identifier )
	{
		$message_data = self::$transactions[$identifier];
		$message_data[3]['duration'] = microtime(true) - $message_data[4];
		
		self::add( $message_data[0], $message_data[1], $message_data[2], $message_data[3] );
	}
}