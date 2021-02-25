<?

load::system('cache/abstract_cache');

class mem_cache extends abstract_cache
{
	private static $instance;
	private $resource;

	private $inner_cache_enabled = true;
	private $inner_cache = array();

	private function __construct() {}

	public function get_resource()
	{
		if ( !$this->resource )
		{
			$config = conf::get('mamcached');
			$this->connect($config['host'], $config['port']);
		}

		return $this->resource;
	}

	public function disable_inner_cache()
	{
		$this->inner_cache = array();
		$this->inner_cache_enabled = false;
	}

	public function enable_inner_cache()
	{
		$this->inner_cache_enabled = true;
	}

	public function connect( $host, $port )
	{
		$log_id = ( conf::get('enable_log') ) ? logger::start($key, 'Memcached connection', logger::LEVEL_NORMAL) : null;
		$this->resource = memcache_connect($host, $port);
		conf::get('enable_log') ? logger::commit($log_id) : null;
	}

	public function get( $key )
	{
		$key = $this->get_key( $key );

		if ( $this->inner_cache_enabled && array_key_exists($key, $this->inner_cache) )
		{
			return $this->inner_cache[$key];
		}

		$log_id = ( conf::get('enable_log') ) ? logger::start($key, 'Memcached', logger::LEVEL_NORMAL) : null;
		$data = memcache_get( $this->get_resource(), $key );

		if ( $this->inner_cache_enabled )
		{
			$this->inner_cache[$key] = $data['value'];
		}

		conf::get('enable_log') ? logger::commit($log_id) : null;

		return $data['value'];
	}

	public function get_key( $key )
	{
		$config = conf::get('mamcached');
		return $config['hash'] . $key;
	}

	public function exists( $key )
	{
		$key = $this->get_key( $key );
		return (bool) memcache_get( $this->get_resource(), $key );
	}

	public function set( $key, $value, $expire = null, $tags = array() )
	{
		$key_original = $key;
		$key = $this->get_key( $key );

		if ( !$expire )
		{
			$config = conf::get('mamcached');
			$expire = $config['expiration'];
		}

		$data = array(
			'value' => $value
		);

		memcache_set($this->get_resource(), $key, $data, 0, $expire);

		if ( $this->inner_cache_enabled )
		{
			$this->inner_cache[$key] = $value;
		}

		foreach ( (array)$tags as $tag )
		{
			if ( !$data = memcache_get( $this->get_resource(), "tag.{$tag}" ) )
			{
				$data = array();
			}

			$data[] = $key_original;

			memcache_set( $this->get_resource(), "tag.{$tag}", $data, 0, $expire );
		}
	}
	
	public function delete( $key )
	{
		$key = $this->get_key( $key );

		$this->get_resource()->delete( $key );
	}

	public function delete_tag( $tag )
	{
		if ( $data = (array)memcache_get( $this->get_resource(), "tag.{$tag}" ) )
		{
			foreach ( $data as $key )
			{
				$this->delete($key);
			}
		}
	}

	/**
	 * @return mem_cache
	 */
	public static function i()
	{
		if ( !self::$instance )
		{
			self::$instance = new self;
		}

		return self::$instance;
	}
}
