<?

class shell_error
{
	public static function handle()
	{
		set_error_handler( 'shell_error::errors' );
		set_exception_handler('shell_error::exceptions');
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
	    }
	
	    return true;
	}
	
	public static function render( $title, $data )
	{
		echo "\033[31m";
		echo "\n";
		echo '-----ERROR-----' . "\n";
		echo $title . "\n";
		echo 'File: ' . $data['file'] . "\n";
		echo 'Line: ' . $data['line'] . "\n";
		echo 'Code: ' . $data['code'] . "\n";
		
		if ( $data['sql'] )
		{
			echo 'SQL: ' . $data['sql'] . "\n";
		}
		
		echo "\033[0m";
	}
}