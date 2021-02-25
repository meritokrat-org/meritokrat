<?

class ooops_leave_action extends frontend_controller
{
	public function execute()
	{
            $href = urldecode(request::get_string('href'));
            if(session::get_user_id()==5968) {
                    var_dump($href);
                    die();
                }
            if(!$href)
               $this->redirect('/');

            if(strpos($href,conf::get('server'))!==false || strpos($href,'.')===false)
            {
                $href = str_replace('www.','',$href);
                if(strpos($href,'f.'.conf::get('server'))!==false)
                {
                    $subdomen = 'f.';
                    $href = str_replace('http://f.'.conf::get('server'),'',$href);
                }
                elseif(strpos($href,'image.'.conf::get('server'))!==false)
                {
                    $subdomen = 'image.';
                    $href = str_replace('http://image.'.conf::get('server'),'',$href);
                }
                elseif(strpos($href,'ru.'.conf::get('server'))!==false)
                {
                    $subdomen = 'ru.';
                    $href = str_replace('http://ru.'.conf::get('server'),'',$href);
                }
                elseif(strpos($href,'en.'.conf::get('server'))!==false)
                {
                    $subdomen = 'en.';
                    $href = str_replace('http://en.'.conf::get('server'),'',$href);
                }
                else 
                {
                    $href = str_replace('http://'.conf::get('server'),'',$href);

             
                }
                
                if(!$href)$href = 'home';
                $this->redirect('http://'.$subdomen.conf::get('server').'/'.trim($href,'/'));
            }
            
            $this->disable_layout();
            if(strpos($href,'https://')!==false)
            {
                $http = 'https://';
            }
            else
            {
                $http = 'http://';
            }
            
            $this->href = $http.str_replace($http,'',$href);
	}
}
