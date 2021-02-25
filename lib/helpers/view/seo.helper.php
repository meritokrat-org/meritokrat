<?php

class seo_helper
{

    protected static $action_title = null;

    public static function getModules($directory)
    {
        $dir = opendir($directory);
        if (is_dir($directory)) {
            while ($d = readdir($dir)) {
                if ($d != '.' && $d != '..' && $d != '' && $d != '.svn')
                    if (is_dir($directory . $d)) {
                        $modules[] = $d;
                    }
            }
            closedir($dir);
        }
        sort($modules);

        return $modules;
    }

    public static function getText($module, $action, $lang = 'ua')
    {
        $list = seo_text_peer::instance()->get_list(['module' => $module, 'action' => $action, 'hidden' => 0]);
        if (!empty($list))
            $textData = seo_text_peer::instance()->get_item($list[0]);

        return ($textData['text_' . $lang]) ? $textData['text_' . $lang] : false;
    }

    public static function set_title($title = null)
    {
        if ($title) self::$action_title = $title;
    }

    public static function getTitle($module, $action, $lang = 'ua')
    {
        $titleParts = [];
        $tags = self::getTags($module, $action, $lang);

        if ($tags['tags']['title']) {
            $titleParts[] = $tags['tags']['title'];
        }

        if ($tags['module_title']) {
            $titleParts[] = $tags['module_title'];
        }

        $titleParts[] = $tags['main_title'];

        return implode(' - ', $titleParts);
    }

    public static function get_title($module, $action, $lang = 'ua')
    {
        return sprintf('<title>%s</title>', self::getTitle($module, $action, $lang));
    }

    public static function getTags($module, $action, $lang = 'ua')
    {
        $mainTitle   = self::getMainTitle($lang);
        $moduleTitle = self::getModuleTitle($module);
        $actionTags  = self::getActionTags($module, $action, $lang);

        return ['main_title' => $mainTitle, 'module_title' => $moduleTitle, 'tags' => $actionTags];
    }

    public static function getMainTitle($lang = 'ua')
    {
        $tagData = seo_tags_peer::get_action_tags('home', 'main_title');

        return ($tagData['title_' . $lang]) ? $tagData['title_' . $lang] : t('Социальная сеть Меритократ.org');
    }

    public static function getModuleTitle($module, $lang = 'ua')
    {
        $tagData = seo_tags_peer::get_module_tags($module);

        return ($tagData['title_' . $lang]) ? ($tagData['title_' . $lang]) : false;
    }

    public static function getActionTags($module, $action, $lang = 'ua')
    {
        $tagData = seo_tags_peer::get_action_tags($module, $action);
        if ($tagData) {
            switch ($tagData['ttype']) {
                case seo_tags_peer::NONE_TYPE:
                    $title = false;
                    break;
                case seo_tags_peer::STATIC_TYPE:
                    $title = ($tagData['title_' . $lang]) ? $tagData['title_' . $lang] : false;
                    break;
                case seo_tags_peer::DYNAMIC_TYPE:
                    $title = self::$action_title;
                    break;
                default :
                    $title = false;
                    break;
            }
            $retVal['title']       = $title;
            $retVal['keywords']    = ($tagData['keywords_' . $lang]) ? $tagData['keywords_' . $lang] : false;
            $retVal['description'] = ($tagData['description_' . $lang]) ? $tagData['description_' . $lang] : false;

            return $retVal;
        } else
            return false;

    }

    public static function get_meta($module, $action, $lang = 'ua')
    {
        $tags = self::getTags($module, $action, $lang);
        $keys = ($tags['tags']['keywords']) ? '<meta name="keywords" content="' . $tags['tags']['keywords'] . '">' : '';
        $desc = ($tags['tags']['description']) ? '<meta name="description" content="' . $tags['tags']['description'] . '">' : '';

        return ($keys || $desc) ? $keys . $desc : false;
    }

}

?>
