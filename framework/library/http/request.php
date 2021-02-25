<?

class request
{
	public static function get( $name, $default = null )
	{
		if ( $_REQUEST[$name] !== null )
		{
			return self::cleaner($_REQUEST[$name]);
		}
		
		return $default;
	}
	
	public static function get_int( $name, $default = 0)
	{
		$value = $_REQUEST[$name];
		
		return $value !== null ? (int)$value : $default;
	}

	public static function get_bool( $name, $default = false )
	{
		$value = self::get( $name );

		return $value !== null ? (bool)$value : (bool)$default;
	}
	
	public static function get_string( $name, $default = '' )
	{
		$value = self::get( $name );
		
		return $value !== null ? (string)$value : $default;
	}

	public static function get_all()
	{
		return $_REQUEST;
	}

	public static function get_file( $name )
	{
		return $_FILES[$name];
	}

	public static function cleaner( $value )
	{
            //$search=array("|","../","||","\"","/\\\$/","$","\\","^","%");
            //$replace=array("I",",,/","I","&#34;","&#36;","&#36;","&#92;","","&#37;");
            //$clean_value=str_replace($search, $replace, $value);
/*            if (strpos(strtolower($value),",char(") or strpos(strtolower($value),"script>"))
                {
                    load::system('email/email');
                    $email = new email();
                    $email->setSubject(conf::get('project-name') . ': кто-то че-то попутал на ' . $_SERVER['SERVER_NAME'] . ', ' . date('d M, Y'));
                    $email->setReceiver(conf::get('debug_email_address'));

                    $body = "\n\n";
                    if ( session::is_authenticated() )
                    {
                            $body .= 'Пользователь: ' . user_helper::full_name(session::get_user_id(), false) . "\n";
                            $body .= 'http://' . context::get('host') . '/profile-' . session::get_user_id() . "\n\n";
                    }
                    $body .= 'URL: ' . $_SERVER['REQUEST_URI'] . "\n\n";
                    $body .= 'Referrer: ' . $_SERVER['HTTP_REFERER'] . "\n\n";
                    $body .= "\n\n Значение:";
                    $body .= $value;
                    $body .= "\n\n";
                    $body .= print_r($_SERVER, 1);
                    $email->setBody($body);
                    $email->send();
                    throw new public_exception( t('Загроза безпеки серверу, повідомлення про помилку відправлено адміністратору') );
                }
                elseif (strpos(strtolower($value),"union") or strpos(strtolower($value),"drop") or strpos(strtolower($value),"%"))
                {
                    load::system('email/email');
                    $email = new email();
                    $email->setSubject(conf::get('project-name') . ': подозрения всякие ' . $_SERVER['SERVER_NAME'] . ', ' . date('d M, Y'));
                    $email->setReceiver(conf::get('debug_email_address'));

                    $body = "\n\n";
                    if ( session::is_authenticated() )
                    {
                            $body .= 'Пользователь: ' . user_helper::full_name(session::get_user_id(), false) . "\n";
                            $body .= 'http://' . context::get('host') . '/profile-' . session::get_user_id() . "\n\n";
                    }
                    $body .= 'URL: ' . $_SERVER['REQUEST_URI'] . "\n\n";
                    $body .= 'Referrer: ' . $_SERVER['HTTP_REFERER'] . "\n\n";
                    $body .= "\n\n Значение:";
                    $body .= $value;
                    $body .= "\n\n";
                    $body .= print_r($_SERVER, 1);
                    $email->setBody($body);
                    $email->send();
                }*/
            $clean_value=preg_replace("#<(/*)(script|xss)(.*?)\>#si", '',  $value);
            return $clean_value;
	}
}
