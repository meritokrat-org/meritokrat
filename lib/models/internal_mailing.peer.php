<?php
class internal_mailing_peer extends db_peer_postgre
{
	protected $table_name = 'internallist_archive';

	public static function instance()
	{
		return parent::instance( 'internal_mailing_peer' );
	}
        public static function getactive($v) {
            $ids = self::instance()->get_list(array('active'=>$v));
            $data = array();
            foreach($ids as $key=>$value)
                $data[] = self::instance()->get_item($value);
            return $data;

        }
        public static function getFilters($name) {
            $types = array(
                            'common'=>'Усім',
                            'group'=>'Групи',
                            'status'=>'Статус',
                            'func'=>'Функції',
                            'region'=>'Регіони',
                            'district'=>'Регіон/Район',
                            'sferas'=>'Сфери',
                            'visit'=>'Відвідування',
                            'lists'=>'Списки'
                        );
            return $types[$name];
        }
}

?>
