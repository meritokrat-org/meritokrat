<?

load::system('shell/application');

class shell_task
{
	protected $arguments = array();
	protected $silent = false;
	
	protected $color_map = array(
		'black' => '30',
		'blue' => '34',
		'green' => '32',
		'cyan' => '36',
		'red' => '31',
		'purple' => '35',
		'brown' => '33',
		'silver' => '37'
	);
	
	public function __construct( $arguments )
	{
		foreach ( $arguments as $arg )
		{
			if ( strpos($arguments, $arg) )
			{
				$this->arguments[] = $arg;
			}
			else
			{
				$arg_data = explode('=', $arg);
				$this->arguments[trim($arg_data[0])] = trim($arg_data[1]);
			}
		}
	}
	
	public function out( $message = null, $color = null )
	{
		if ( $this->silent ) return;

		if ( $color )
		{
			if ( $mapped = $this->color_map[$color] )
			{
				echo "\033[{$mapped}m";
			}
		}
		
		echo $message . "\n";
		
		if ( $color )
		{
			echo "\033[0m";
		}
	}

	public function notice( $message )
	{
		$this->out($message, 'brown');
	}
	
	public static function run()
	{
		$arguments = $_SERVER['argv'];
		array_shift($arguments);
		
		if ( !$arguments )
		{
			die("Enter task name\n");
		}
		
		$task_name = array_shift($arguments);

		load::task($task_name);
		
		$task_class = "{$task_name}_task";
		$start_ts = microtime(true);
		
		$app = new shell_application();
		$app->init();
		
		$task = new $task_class( $arguments );
		$task->out('Running task ' . $task_name);
		$task->out();

		try
		{
			$task->execute();
		} catch ( FinishExcepion $e ) {}
		
		$delta = ceil((microtime( true ) - $start_ts) * 1000 ) / 1000;
		
		$task->out();
		$task->out('Executed task successfully in ' . $delta . ' seconds' );
	}

	public function finish_task()
	{
		throw new FinishExcepion();
	}
}

class FinishExcepion extends Exception {};