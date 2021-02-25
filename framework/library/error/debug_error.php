<?

class debug_error
{
	public static function handle()
	{
		set_error_handler( 'debug_error::errors' );
		set_exception_handler('debug_error::exceptions');
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
	
	    return true;
	}
	
	public static function render( $title, $data )
	{
		$trace_html = '<table style="font-size: 11px;color: gray;" cellspacing="5">';
		$trace_html .= '<tr style="font-weight: bold;"><td>Class</td><td>Function</td><td>File</td></tr>';
		foreach ( $data['backtrace'] as $trace_data )
		{
			$trace_html .=
			"<tr><td>{$trace_data['class']}</td><td>{$trace_data['function']}</td><td>{$trace_data['file']} ({$trace_data['line']})</td></tr>";
		}
		$trace_html .= '</table>';
		
		$html =
		'
		<div style="font-family: verdana; margin: 10px; padding: 10px; background: #F7F7F7; border: 1px solid maroon;">
			<h3>' . $title . '</h3>
			<table style="font-size: 11px; color: gray;">
				<tr><td>File:</td><td>' . $data['file'] . ' (line ' . $data['line'] . ')</td></tr>
				<tr><td>Code:</td><td>' . $data['code'] . '</td></tr>
				' . ( $data['sql'] ? '<tr><td>SQL:</td><td>' . $data['sql'] . '</td></tr>' : '' ) . '
			</table>
			<br />
			<b>Backtrace</b> <br />' . $trace_html . '
		</div>';
		
		echo $html;
	}
}