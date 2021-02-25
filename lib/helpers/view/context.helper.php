<?

class context_helper
{
	public static function help( $text )
	{
		$html =
		'<div class="box_content p10 ml10 fs11 quiet">
		' . tag_helper::image('icons/help.png', array('class' => 'left m10')) . '
		<div style="margin-left: 60px;">' . $text . '</div><div class="clear"></div></div>';

		return $html;
	}
}