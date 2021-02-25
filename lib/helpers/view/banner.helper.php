<?

load::view_helper('tag', true);

class banner_helper
{
	public static function photo( $id, $size = 'p', $options = array(), $linked = true )
	{
            $sizes=array('p' => '200',
                'm' => '160',
                'r' => '90',
                'mm' => '200',
                'mp' => '300',
                'ma' => '160',
                't' => '72',
                'b' => '78',
                's' => '54',
                'sm' => '54',
                'f' => '640');
            return tag_helper::image(self::photo_path($id, $size), $options, context::get('image_server'));
        }

	public static function photo_path( $id, $size = 'p' )
	{
            return "{$size}/banners/".substr(md5($id),0,10).".jpg";
	}
}