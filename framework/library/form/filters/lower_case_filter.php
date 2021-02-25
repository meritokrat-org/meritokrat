<?

class lower_case_filter extends abstract_filter
{
	public function value($value)
	{
		return mb_strtolower($value);
	}
}