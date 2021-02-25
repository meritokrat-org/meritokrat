<?

class element_helper
{
	public static function selector( $name, $options = array() )
	{
		$data = $options['data'];
		$value = $options['value'];
		$text = $options['text'];

		$id = str_replace(array('[', ']'), array('_', ''), $name);

		$editable = $options['editable'] ? 'true' : 'false';

		$input = '<input type="text" class="selector little hidden" name="' . $name . '" id="' . $id . '" value="' . $value . '" />';
		$html = $input . '<div id="' . $id . '_container" class="selector little" onclick="Selector(this, ' . $data . ', { editable: ' . $editable . '});"><div class="value">' . $text . '</div><div class="more"></div><div class="clear"></div><div class="list"></div></div>';
	
		return $html;
	}
}