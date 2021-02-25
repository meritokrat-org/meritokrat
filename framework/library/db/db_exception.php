<?

class dbException extends Exception
{
	protected $sql;
		
	public function __construct( $message, $code = null, $sql = '' )
	{
		parent::__construct('[SQL] ' . $message, $code);
		$this->sql = $sql;
	}
	
	public function getSql()
	{
		return $this->sql;
	}
}