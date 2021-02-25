<?

load::system('form/validators/validators_factory');
load::system('form/filters/filters_factory');

abstract class form
{
	protected $errors = array();
	protected $elements = array();
	protected $validators = array();
	protected $filters = array();

	public function  __construct()
	{
		$this->set_up();
	}

	public function add_element( $name, $default_value = null )
	{
		$this->elements[$name] = $default_value;
	}

	public function get_value( $name )
	{
		return $this->elements[$name];
	}

	public function set_value( $name, $value )
	{
		if ( array_key_exists($name, $this->elements) )
		{
			if ( $this->filters[$name] )
			{
				foreach ( $this->filters[$name] as $filter )
				{
					$value = $filter->value($value);
				}
			}
			
			$this->elements[$name] = $value;
		}
	}

	public function set_values( $data )
	{
		foreach ( $data as $name => $value )
		{
			$this->set_value($name, $value);
		}
	}

	public function get_values()
	{
		return $this->elements;
	}

	public function load_from_request()
	{
		$this->set_values( request::get_all() );
		$this->set_values( $_FILES );
	}

	public function add_validator( $name, $validator )
	{
		if ( is_string($validator) )
		{
			$validator = validators_factory::get($validator);
			$validator->set_form($this);
		}

		$this->validators[$name][] = $validator;
	}

	public function add_filter( $name, $filter )
	{
		if ( is_string($filter) )
		{
			$filter = filters_factory::get($filter);
		}

		$this->filters[$name][] = $filter;
	}

	public function is_valid()
	{
		foreach ( $this->elements as $name => $value )
		{
			if ( is_array($this->validators[$name]) )
			{
				foreach ( $this->validators[$name] as $validator )
				{
					if ( !$validator->is_valid($value) )
					{
						$this->errors[$name][] = $validator->get_error();
					}
				}
			}
		}

		return !(bool)$this->errors;
	}

	/**
	 * Get all errors from stack
	 */
	public function get_errors()
	{
		return $this->errors;
	}

	/**
	 * Get first error on stack
	 */
	public function get_error()
	{
		$error = array_shift($this->errors);
		return array_shift($error);
	}

	abstract function set_up();
}