<?php

class cookie
{
	public static function set( $name, $value, $expire = null, $path = null, $domain = null )
	{
		setcookie( md5($name), $value, $expire, $path, $domain );
	}
	
	public static function delete( $name )
	{
		self::set( $name, null );
	}
	
	public static function get( $name )
	{
		return $_COOKIE[md5($name)];
	}
}