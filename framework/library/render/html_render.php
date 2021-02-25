<?

load::system('render/abstract_render');

class html_render extends abstract_render
{
	public function render()
	{
		load::view_helper('tag', true);
		
		foreach ( $this->controller as $key => $value )
		{
			$$key = $value;
		}
		
		$controller = $this->controller;
		
		if ( $this->controller->get_layout() )
		{
			$template = $this->controller->get_layout_path();
		}
		else
		{
			$template = $this->controller->get_template_path();
		}
		
		ob_start();
		
		include $template;
		
		return ob_get_clean();
	}
	
	public function render_debug()
	{
		$log_data = logger::get();
		
		$log_html = '';
		foreach ( $log_data as $tag => $data )
		{
			$level = $data[0]['level'];
			
			switch ( $level )
			{
				case logger::LEVEL_CRITICAL:
					$color = 'red';
					break;
				case logger::LEVEL_WARNING:
					$color = '#AAAA00';
					break;
				
				default:
					$color = 'green';
			}
			
			$tag_duration = 0;
			$tag_html = '';
			
			foreach ( $data as $log_item )
			{
				$tag_html .= '<tr><td style="border-bottom:1px solid #EEE;"><span style="color:' . $color . '">' . $log_item['message'] . '</span>' . '</td><td style="border-bottom:1px solid #EEE;"><span style="color: ' . ( $log_item['duration'] < 0.01 ? 'black' : 'red' ) . '">' . (ceil($log_item['duration'] * 10000)/10000) . '</span></td></tr>';
				$tag_duration += $log_item['duration'];
			}
			
			$log_html .= '<div style="color:' . $color . '; cursor: pointer; border-top: 1px solid #DDD; font-weight: bold;" onclick="$(\'#log_tag_' . md5($tag) . '\').toggle();"><span style="float:left;">' . $tag . ' (' . count($data) . ')&nbsp;&nbsp;&nbsp;</span><span style="font-weight: normal; color: black;float: right;">' . (ceil($tag_duration * 10000)/10000) . 's</span></div><div id="log_tag_' . md5($tag) . '" style="display:none;">';
			$log_html .= '<br clear=all /><table cellpadding=0 cellspacing=0>' . $tag_html . '</table>';
			$log_html .= '</div><br clear=all />';
		}
		
		$html =
		'<div id="web_debug" style="position:absolute; top:' . (int)$_COOKIE['WEB_DEBUG_POS_TOP'] . 'px; left: ' . (int)$_COOKIE['WEB_DEBUG_POS_LEFT'] . 'px; border: 1px solid #DDD; background: #FAFAFA; padding: 5px; font-size: 10px; font-family: Sans Serif;">
			<div><b>Debug</b> <a href="javascript:;" onclick="$(\'#web_debug\').hide();">[x]</a></div>
			<div id="web_debug_log">
				' . $log_html . '
			</div>
            <div style="border-top: 1px solid #DDD; display:none; width: 250px;" id="ajax_debug"></div>
		</div>';
		
		return $html;
	}
}