<?

abstract class abstract_render
{
	/**
	 * @var basic_controller
	 */
	protected $controller;
	
	public function __construct( $controller )
	{
		$this->controller = $controller;
	}
	
	abstract public function render();
}