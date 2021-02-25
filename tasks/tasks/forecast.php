<?

class forecast_task extends shell_task
{
	public function execute()
	{
		$this->results = array(
			array('id' => 102398, 'votes' => 35.32),	
			array('id' => 102390, 'votes' => 25.05),	
			array('id' => 104483, 'votes' => 13.06),	
			array('id' => 102400, 'votes' => 6.96),	
			array('id' => 104033, 'votes' => 5.45)
		);

		load::model('candidates/forecast');
		$users = db::get_cols('SELECT DISTINCT user_id FROM ' . candidates_forecast_peer::instance()->get_table_name());

		$min_delta = 25;

		foreach ( $users as $user_id )
		{

			echo '.';
			$forecast = candidates_forecast_peer::instance()->get_by_user( $user_id );
			
			$delta = $this->check_forecast( $forecast );

			if ( $min_delta > $delta )
			{
				$min_delta = $delta;
				$min_user_id = $user_id;
			}
		}

		$this->out( 'Delta: ' . $min_delta . '; user: ' . $min_user_id );
		$this->out( print_r(candidates_forecast_peer::instance()->get_by_user( $min_user_id ), 1) );
		
	}

	public function check_forecast( $forecast )
	{
		$delta = 0;

		if ( count($forecast) != 5 )
		{
			echo 'E';
			return 100;
		}

		foreach ( $forecast as $i => $data )
		{
			if ( $data['candidate_id'] != $this->results[$i]['id'] )
			{
				$delta += 5; continue;
			}

			$delta += abs($this->results[$i]['votes'] - $data['votes']);
		}

		return $delta;
	}
}
