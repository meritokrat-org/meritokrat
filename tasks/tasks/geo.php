<?

class geo_task extends shell_task
{
	public function execute()
	{
		mysql_connect('localhost', 'root', 'root');
		mysql_select_db('geo');
		mysql_query('SET names utf8');
		$r = mysql_query('SELECT * FROM regions WHERE country = 2 AND parent_id IS NULL');
		while ( $row = mysql_fetch_assoc($r) )
		{
			$regions[] = $row;
		}

		load::model('geo');

		foreach ( $regions as $region )
		{
			$region_id = geo_peer::instance()->insert_region(array(
				'name_ru' => $region['name'],
				'name_ua' => $region['name_ua'],
			));

			$r = mysql_query('SELECT * FROM towns WHERE region = ' . $region['id']);
			while ( $city = mysql_fetch_assoc($r) )
			{
				geo_peer::instance()->insert_city(array(
					'region_id' => $region_id,
					'name_ru' => $city['name'],
					'name_ua' => $city['name_ua'],
				));
				echo '.';
			}
		}
	}
}