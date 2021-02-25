<?

class tag_helper
{
    public static $rss = '';

    public static function file_path($src, $server = null)
    {
        if ($server === null) {
            $server = conf::get('subdomains-disabled') ? context::get('server') : context::get('file_server');
        }

        return $server . $src;
    }

    public static function select($name, $data, $options = array())
    {
        $options['name'] = $name;

        if (!$options['use_values']) {
            $options['use_values'] = false;
        }

        $html = '<select ' . self::get_options_html($options) . '>';
        foreach ($data as $key => $value) {
            $val = ($options['use_values'] ? $value : $key);
            if ($value !== '&mdash;') {
                $value = htmlspecialchars($value);
            }
            $html .= '<option value="' . htmlspecialchars(
                    $val
                ) . '" ' . ($val == $options['value'] ? 'selected' : '') . '>' . $value . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public static function get_options_html($options)
    {
        $html = '';

        foreach ($options as $option => $value) {
            $html .= ' ' . $option . '="' . htmlspecialchars($value) . '"';
        }

        return $html;
    }

    public static function select_first_epmty($name, $data, $options = array())
    {
        $options['name'] = $name;

        if (!$options['use_values']) {
            $options['use_values'] = false;
        }

        $html = '<select ' . self::get_options_html($options) . '>';
        $data[-1] = '&mdash;';
        ksort($data);
        foreach ($data as $key => $value) {
            $val = ($options['use_values'] ? $value : $key);
            $selected = '';
            if ($value != '&mdash;') {
                $value = htmlspecialchars($value);
            }
            if ($val == -1) {
                $val = '';
            }
            if ($val === '' && !$options['value']) {
                $selected = 'selected';
            }
            if ($val == $options['value']) {
                $selected = 'selected';
            }
            $html .= '<option value="' . htmlspecialchars($val) . '" ' . $selected . '>' . $value . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public static function wait_panel($id = 'wait_panel', $options = [])
    {
        $default_options = ['width' => 15, 'align' => 'absmiddle', 'class' => 'hidden ml10'];
        $options['id'] = $id;

        $options = array_merge($options, $default_options);

        return tag_helper::image('common/loading.gif', $options);
    }

    public static function image($src, $options = [], $server = null)
    {
        if ($src === '/icons/ua.png') {
            return 'УКР';
        }
        if ($src === '/icons/ru.png') {
            return 'РУС';
        }
        if ($src === '/icons/en.png') {
            return 'EN';
        }

        $options['src'] = self::image_path($src, $server);

        return self::tag('img', $options);
    }

    public static function image_path($src, $server = null)
    {
        if (null === $server) {
            $server = conf::get('subdomains-disabled') ? context::get('server') : context::get('static_server');
        }

        return $server . $src;
    }

    public static function tag($name, $options = array(), $block = false)
    {
        $options_html = self::get_options_html($options);

        return "<{$name}{$options_html}" . ($block ? "></{$name}>" : '/>');
    }

    public static function css($src)
    {
        $src = $src . '?' . conf::get('static_hash', 1);
        $src = (conf::get('subdomains-disabled') ? context::get('server') : context::get('static_server')) . $src;

        $options = array(
            'href' => $src,
            'rel' => 'stylesheet',
            'type' => 'text/css',
        );

        return self::tag('link', $options);
    }

    public static function js($src)
    {
        $src = $src . (strpos($src, '?') !== false ? '&' : '?') . conf::get('static_hash', 1);
        $src = (conf::get('subdomains-disabled') ? context::get('server') : context::get('static_server')) . $src;

        $options = array(
            'src' => $src,
            'type' => 'text/javascript',
        );

        return self::tag('script', $options, true);
    }

    public static function rss()
    {
        if (self::$rss) {
            $html = '<link rel="alternate" type="application/rss+xml" title="RSS" href="' . self::$rss . '">';

            return $html;
        }
    }

    public static function get_short($string, $num = 80)
    {
        $string = stripslashes(htmlspecialchars($string));
        if ($num > mb_strlen($string)) {
            return $string;
        } else {
            $pos = mb_strpos($string, ' ', $num);

            return mb_substr($string, 0, ($pos > 60 ? $pos : $num)) . ' ...';
        }
    }
}
