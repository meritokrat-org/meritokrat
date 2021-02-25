<?

load::system('storage/abstract_storage');

class storage_simple extends abstract_storage
{

    public $enable_extensions = false;

    public function get($key)
    {
        if ($this->exists($key)) {
            return file_get_contents($this->get_path($key), $data);
        }
    }

    public function exists($key)
    {
        return is_file($this->get_path($key));
    }

    public function set($key, $data)
    {
        $path = $this->get_path($key);
        $this->prepare_path($path);

        file_put_contents($path, $data);
    }

    public function prepare_path($path)
    {
        $dir = dirname($path);

        $create = array();

        while (!is_dir($dir)) {
            $create[] = $dir;
            $dir = dirname($dir);
        }

        $create = array_reverse($create);

        foreach ($create as $dir) {
            mkdir($dir, 0777);
            chmod($dir, 0777);
        }
    }

    public function get_path($key, $absolute_path = true)
    {
        $hash = md5($key);
        $file_path = '';

        for ($i = 0; $i < 4; $i++)
            $file_path .= substr($hash, $i * 2, 2) . '/';

        $file_path .= md5($hash);

        if ($this->enable_extensions) {
            $file_path .= '.' . pathinfo($key, PATHINFO_EXTENSION);
        }

        $path = $file_path;
        if ($absolute_path) {
            $path = conf::get('file_storage_path') . '/' . $path;
        }

        return $path;
    }

    public function save_uploaded($key, $file_data)
    {
        $path = $this->get_path($key);
        $this->prepare_path($path);

        move_uploaded_file($file_data['tmp_name'], $path);
    }


    public function save_from_path($key, $src)
    {
        @$path = $this->get_path($key);
        @$this->prepare_path($path);

        @copy($src, $path) or mail('andimov@gmail.com', 'storage simple, string 90', $src . ' ' . $path . ' ' . $key);;
    }

    public function move_from_path($key, $src)
    {
        @$path = $this->get_path($key);
        @$this->prepare_path($path);
        if (file_exists($src)) copy($src, $path);
    }

    public function img_crop($src, $key, $x, $y, $width, $height, $rgb = 0xFFFFFF, $quality = 100)
    {

        @$dest = $this->get_path($key);
        @$this->prepare_path($dest);


        if (!file_exists($src)) {
            echo "Image " . $src . " not found<br>";
            return false;
        }

        $size = getimagesize($src);

        if ($size === false) {
            echo "getImageSize error<br>";
            return false;
        }

        if ($width == 0 && $height == 0) {
            // echo '!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!';
            $W = $size[0];
            $H = $size[1];
//                echo "W =".$W."<BR>";
//                echo "H =".$H."<BR>";

            $width = min($W, $H) * 0.8;
            $height = min($W, $H) * 0.8;

//                echo "w =".$width."<BR>";
//                echo "h =".$height."<BR>";

            $x = ($W - $width) / 2;
            $y = ($H - $height) / 2;

//                echo "x =".$x."<BR>";
//                echo "y =".$y."<BR>";
        }

        $format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));
        $icfunc = 'imagecreatefrom' . $format;

        if (!function_exists($icfunc)) {
            echo "Unknown format?<br>";
            return false;
        }

        $isrc = $icfunc($src);
        $idest = imagecreatetruecolor($width, $height);

        imagefill($idest, 0, 0, $rgb);
        imagecopyresampled($idest, $isrc, 0, 0, $x, $y, $width, $height, $width, $height);

        imagejpeg($idest, $dest, $quality);

        imagedestroy($isrc);
        imagedestroy($idest);
        exec("convert {$dest} -resize 200 {$dest}");
        return true;
    }
}
