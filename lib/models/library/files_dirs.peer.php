<?

class library_files_dirs_peer extends db_peer_postgre
{
	protected $table_name = 'files_dirs';

	/**
	 * @return files_dirs_peer
	 */
	public static function instance()
	{
		return parent::instance( 'library_files_dirs_peer' );
	}

       
	
	public function get_item( $primary_key )
	{
	
                $sql = 'SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :id LIMIT 1';
                $bind = array('id' => $primary_key);
                $data = db::get_row( $sql, $bind, $this->connection_name );
		return $data;
	}
}