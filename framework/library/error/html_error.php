<?

class html_error
{
	public static function handle()
	{
		set_error_handler( 'html_error::errors' );
		set_exception_handler('html_error::exceptions');
	}
	
	public static function exceptions( Exception $e )
	{
		$data = array(
    		'line' => $e->getLine(),
    		'file' => $e->getFile(),
    		'code' => $e->getCode(),
    		'backtrace' => $e->getTrace()
    	);
    	
    	if ( $e instanceof dbException )
    	{
    		$data['sql'] = $e->getSql();
    	}
    	
    	self::render( $e->getMessage(), $data );
        exit;
	}
	 
	public static function errors( $errno, $errstr, $errfile, $errline )
	{
	    switch ( $errno )
	    {
		    case E_USER_NOTICE:
		    case E_NOTICE:
			case E_STRICT:
		    	return;
		    	break;
		
		    default:
		    	$backtrace = debug_backtrace();
				array_shift($backtrace);
				
		    	$data = array(
		    		'line' => $errline,
		    		'file' => $errfile,
		    		'code' => $errno,
		    		'backtrace' => $backtrace 
		    	);
		    	
		    	self::render( $errstr, $data );
		        exit;
	    }
	}
	
	public static function render( $title, $data )
	{
		$error_module = conf::get('error_handler_module');
		
		load::app( 'modules/' . $error_module . '/actions/index.action');

        context::set('debug_error', array('message' => $title, 'data' => $data));
		
		$action_class = $error_module . '_index_action';
		$controller = new $action_class( $error_module, 'index' );
		context::set_controller( $controller );
		echo $controller->run();
	}
}