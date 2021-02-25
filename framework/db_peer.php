<?

load::system('cache/mem_cache');

abstract class db_peer
{
	protected static $instances = array();
	
	protected $table_name;
	protected $connection_name = null;
	protected $primary_key = 'id';

	protected function __construct()
	{}
	
	/**
	 * @return db_peer
	 */
	public static function instance( $peer )
	{
		if ( self::$instances[$peer] === null )
		{
			self::$instances[$peer] = new $peer;
		}
		
		return self::$instances[$peer];
	}

	public function get_table_name()
	{
		return $this->table_name;
	}
	
	public function get_item( $primary_key )
	{
		$cache_key = get_class($this) . '.' . $primary_key;
		if ( !mem_cache::i()->exists($cache_key) )
		{
			$sql = 'SELECT * FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :id LIMIT 1';
			$bind = array('id' => $primary_key);
			$data = db::get_row( $sql, $bind, $this->connection_name );
			mem_cache::i()->set($cache_key, $data);
		}
		else
		{
			$data = mem_cache::i()->get($cache_key);
		}

		return $data;
	}

	public function delete_item( $primary_key )
	{
		$sql = 'DELETE FROM ' . $this->table_name . ' WHERE ' . $this->primary_key . ' = :id LIMIT 1';
		$bind = array('id' => $primary_key);
		$this->reset_item($primary_key);

		return db::exec( $sql, $bind, $this->connection_name );
	}
	
	public function get_list( $where = array(), $bind = array(), $order = array(), $limit = '', $cache_key = null )
	{
		$where_clause = array();
		foreach ( $where as $key => $value )
		{
			$where_clause[] = "{$key} = :{$key}";
			$bind[$key] = $value;
		}
		
		if ( !$order )
		{
			$order = array( $this->primary_key . ' DESC ');
		}
		
		$where_sql = implode(' AND ', $where_clause);
		$order_sql = implode(', ', $order);
		
		$sql = '
		SELECT ' . $this->primary_key . '
		FROM ' . $this->table_name . 
		( $where_sql ? ' WHERE ' . $where_sql : '' ) .
		( $order_sql ? ' ORDER BY ' . $order_sql : '' ) .
		( $limit ? ' LIMIT ' . $limit : '' );
		
		return db::get_cols( $sql, $bind, $this->connection_name, $cache_key );
	}
	
	public function insert( $data, $ignore_duplicate = false )
	{
		$insert_data = array();
		$insert_columns = array();
		foreach ( $data as $column => $value )
		{
			$insert_data[] = ":{$column}";
			$insert_colunms[] = '`' . $column . '`';
		}
		
		db::exec(
			'INSERT ' . ( $ignore_duplicate ? ' IGNORE ' : '' ) . ' INTO ' . $this->table_name .
			' (' . implode(', ', $insert_colunms) . ') VALUES(' . implode(', ', $insert_data) . ')', $data, $this->connection_name);
		
		return db::last_id( $this->connection_name );
	}

	public function update( $data, $keys = null )
	{
		if ( !$keys )
		{
			$keys = array($this->primary_key);
		}

		$keys_data = array();
		foreach ( $keys as $column )
		{
			$keys_data[] = "`{$column}` = :{$column}";
		}

		$update_data = array();
		foreach ( $data as $column => $value )
		{
			if ( !array_key_exists($column, $keys) )
			{
				$update_data[] = "`{$column}` = :{$column}";
			}
		}

		db::exec(
			'UPDATE ' . $this->table_name . ' SET ' . implode(', ', $update_data) . ' WHERE ' . implode(' AND ', $keys_data) ,
			$data,
			$this->connection_name
		);

		$this->reset_item( $data[$this->primary_key], $data );
	}

	public function reset_item( $primary_key, $data = array() )
	{
		if ( !$data )
		{
			mem_cache::i()->delete( get_class($this) . '.' . $primary_key );
		}
		else if ( $cached_data = mem_cache::i()->get( get_class($this) . '.' . $primary_key ) )
		{
			$data = array_merge($cached_data, $data);
			mem_cache::i()->set( get_class($this) . '.' . $primary_key, $data );
		}
	}
}