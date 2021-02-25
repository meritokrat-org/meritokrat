<?

class trim_filter extends abstract_filter
{
	public function value($value)
	{
		return trim($value);
	}
}