<?

load::app('modules/admin/controller');
class admin_helpedit_action extends admin_controller
{
	public function execute()
	{
            load::model('help/help');

            $id = request::get_int('id');

            if(request::get('submit'))
            {
                $this->set_renderer('ajax');
                $this->json = array();

                $lang = request::get('lang');
                $array['share'] = request::get_int('share');
		
                @db_key::i()->set('text_min_status:'.request::get('alias'),request::get_int('status',0));
                
                
                if($id)
                {
                    $array['id'] = $id;
                    $array['alias'] = trim(request::get('alias'));
		    
                    if(!$this->check_alias($array['alias'],$array['id']))
                    {
                        if( $lang != 'ru' )
                        {
                            $array['title_ua'] = request::get('title_ua');
                            $array['text_ua'] = $this->clean_text(request::get('text_ua'));
                        }
                        else
                        {
                            $array['title_ru'] = request::get('title_ru');
                            $array['text_ru'] = $this->clean_text(request::get('text_ru'));
                        }

                        help_peer::instance()->update($array);
                    }
                }
                else
                {
                    $array['alias'] = request::get('alias');
                    
                    if(!$this->check_alias(request::get('alias')))
                    {
                        if( $lang != 'ru' )
                        {
                            $array['title_ua'] = request::get('title_ua');
                            $array['title_ru'] = request::get('title_ua');
                            $array['text_ua'] = $this->clean_text(request::get('text_ua'));
                        }
                        else
                        {
                            $array['title_ru'] = request::get('title_ru');
                            $array['title_ua'] = request::get('title_ru');
                            $array['text_ru'] = $this->clean_text(request::get('text_ru'));
                        }

                        $id = help_peer::instance()->insert($array);
                        $this->json = array('id'=>$id);
                    }
                }
            }

            $this->info = help_peer::instance()->get_item($id);
        }

        private function check_alias( $alias,$id=0 )
        {
            if(help_peer::instance()->key_exists($alias,$id))
            {
                $this->json = array('errors'=>array('alias'=>array(t('Страница с таким адресом уже существует!'))));
                return true;
            }
            return false;
        }

        private function clean_text( $text )
	{
                $text = stripslashes($text);

                load::lib('purifier/HTMLPurifier.auto');
                load::lib('text/names.extractor');
                
                $pattern_mso="/<!--\[if !mso\]>.*<!\[endif\]-->/sU";
                $pattern_gte_mso="/<!--\[if gte mso.*<!\[endif\]-->/sU";
                @preg_replace($pattern_mso,'',$text,-1,$count);
                @preg_replace($pattern_gte_mso,'',$text,-1,$count);

            	$config = HTMLPurifier_Config::createDefault();
		$config->set("HTML", "SafeEmbed", true);
		$config->set("HTML", "SafeObject", true);
		$config->set('HTML','Allowed','a[href|title|target],br,p[style|class],span[style],em,i,b,u,ul,ol,li,strong,table[style|border|frame|rules],tr,td,img[src|width|height|style],embed,object[type|width|height|data],param[name|value]');
		$HTMLpurifier = new HTMLPurifier($config);
		return $HTMLpurifier->purify($text,$config);
	}
}
