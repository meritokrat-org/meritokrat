<?

abstract class abstract_validator
{
	/**
	 *
	 * @var form
	 */
	protected $form;

	protected $error;

	public function  __construct( form $form = null )
	{
		$this->set_form($form);
	}

	public function set_form( form $form = null )
	{
		$this->form = $form;
	}

	abstract public function is_valid( $value );

	public function get_error()
	{
		return $this->error;
	}

	public function set_error( $title )
	{
		$this->error = $title;
	}
}