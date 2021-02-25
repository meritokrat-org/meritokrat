<?

class browser
{
	public static function get( $url )
	{
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		
		return curl_exec($c);
	}
}