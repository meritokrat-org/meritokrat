<?

class profile_rate_history_action extends frontend_controller
{
	protected $authorized_access = true;
	public function execute()
	{
		load::model('user/trust');
		$list = user_trust_peer::instance()->get_by_user(request::get_int('id'));
		$list = array_reverse($list);

		chdir(conf::get('project_root') . DIRECTORY_SEPARATOR . 'lib/chart/lib');
		load::lib('chart/lib/OFC/OFC_Chart');

		$data_1 = array();
		$data_2 = array();
		$max_trust = 0; $min_trust = $list[0]['trust'];
		$max_not_trust = 0; $min_not_trust = $list[0]['not_trust'];

		foreach ( $list as $trust_data )
		{
			$max_trust = max($max_trust, $trust_data['trust']);
			$max_not_trust = max($max_not_trust, $trust_data['not_trust']);

			$min_trust = min($min_trust, $trust_data['trust']);
			$min_not_trust = min($min_not_trust, $trust_data['not_trust']);

			$data_1[] = $trust_data['trust'];
			$data_2[] = $trust_data['not_trust'];

			$x_labels[] = date('d.m', $trust_data['created_ts']);
		}

		$title = new OFC_Elements_Title( t('График поддержки за 30 дней') );
		$title->set_style('font-size: 8px;');

		$line_1 = new OFC_Charts_Line_Dot();
		$line_1->set_values( $data_1 );
		$line_1->set_halo_size( 0 );
		$line_1->set_width( 2 );
		$line_1->set_dot_size( 3 );
		$line_1->set_colour( '#9BE198' );
		$line_1->set_key( t('доверяют'), 12);

		/*$line_2 = new OFC_Charts_Line_Dot();
		$line_2->set_values( $data_2 );
		$line_2->set_halo_size( 1 );
		$line_2->set_width( 1 );
		$line_2->set_dot_size( 3 );
		$line_2->set_colour( '#E1989B' );
		$line_2->set_key( t('не доверяют'), 12);*/

		$y = new OFC_Elements_Axis_Y();
		$step = ceil(( $max_trust - $min_trust )/4);
		$y->set_range( $min_trust, $max_trust, $step );
		$y->set_grid_colour('#E7F0F8');
		$y->set_colour('#E7F0F8');

		$labels = new OFC_Elements_Axis_X_Label_Set();
		$labels->set_vertical();
		$labels->set_labels( $x_labels );
		$labels->set_colour('#AAAAAA');

		$x = new OFC_Elements_Axis_X();
		$x->set_grid_colour('#E7F0F8');
		$x->set_colour('#E7F0F8');
		$x->set_labels( $labels );

		$chart = new OFC_Chart();
		$chart->set_title( $title );
		$chart->set_bg_colour( '#FFFFFF' );
		$chart->add_element( $line_1 );
		//$chart->add_element( $line_2 );
		$chart->set_y_axis( $y );
		$chart->set_x_axis( $x );

		echo $chart->toString();
		exit;
	}
}