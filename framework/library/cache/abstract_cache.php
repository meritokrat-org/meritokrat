<?

abstract class abstract_cache
{
	abstract public function get( $key );
	abstract public function exists( $key );
	abstract public function set( $key, $value, $expire = null, $tags = array() );
	abstract public function delete( $key );
	abstract public function delete_tag( $tag );
}