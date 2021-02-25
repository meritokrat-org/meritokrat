<?

class photo_comments_peer extends db_peer_postgre
{
	protected $table_name = 'photo_comments';

	public static function instance()
	{
		return parent::instance( 'photo_comments_peer' );
	}

        public function insert($data)
	{
		$id = parent::insert($data);

		$c = 'photo_comments_' . $data['photo_id'];
		mem_cache::i()->delete($c);

		return $id;
	}

	public function get_by_photo( $id )
	{
		$c = 'photo_comments_' . $id;

		if ( !mem_cache::i()->exists($c) )
		{
			$data = $this->get_list(array('photo_id' => $id, 'parent_id' => 0), array(), array('ID ASC'));
			mem_cache::i()->set($c, $data);

		}
		else
		{
			$data = mem_cache::i()->get($c);
		}

		return $data;
	}

	public function delete_item($id)
	{
		$data = $this->get_item($id);
		parent::delete_item($id);
		mem_cache::i()->delete('photo_comments_' . $data['photo_id']);
		parent::reset_item($id);
	}
}