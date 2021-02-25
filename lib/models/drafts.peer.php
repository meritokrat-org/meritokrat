<?
	/**
	 * torwald.ru
	 */
class drafts_peer extends db_peer_postgre
{
	protected $table_name = 'drafts';

	/**
	 * @return mailing_peer
	 */
	public static function instance()
	{
		return parent::instance( 'drafts_peer' );
	}

        public function add_draft($name, $text)
        {
            $data=array("name"=>$name,
                "text"=>$text);
            $id = parent::insert($data);
            return $id;
        }

        function get_drafts($type=0)
        {
            $data = db::get_rows('SELECT id,name FROM drafts WHERE type='.$type);
            foreach($data as $v)$mlist[$v['id']]=$v['name'];
            return $mlist;
        }
}