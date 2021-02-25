<?

load::view_helper('tag', true);

class image_helper
{
	public static function photo( $id, $size = 'p', $folder, $options = array(), $linked = true )
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
            return tag_helper::image(self::photo_path($id, $size, $folder), $options, context::get('image_server'));
        }

	public static function photo_path( $photo, $size = 'p', $folder)
	{ 
            if($photo)
            {
                    return "{$size}/{$folder}/".substr(md5($photo),0,10).".jpg";
            }
            else
            {
                    return "{$size}/group_photo/0.jpg";
            }
	}
}