<?

abstract class abstract_storage
{
	abstract public function get( $key );
	abstract public function exists( $key );
	abstract public function set( $key, $data );
	abstract public function get_path( $key );
	abstract public function prepare_path( $path );
	abstract public function save_uploaded( $key, $uploaded_data );
}