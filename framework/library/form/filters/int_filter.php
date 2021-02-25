<?

class int_filter extends abstract_filter
{
	public function value($value)
	{
		return (int)$value;
	}
}