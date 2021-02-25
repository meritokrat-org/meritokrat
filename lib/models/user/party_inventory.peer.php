<?php

class user_party_inventory_peer extends db_peer_postgre
{
	protected $table_name = 'party_inventory';
	
	/**
	 * @return user_party_inventory_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_party_inventory_peer' );
	}
        
        public function get_inventory_type($id=null) {
            $types = array(
                            1=>t('Футболки'),
                            2=>t('Кепки'),
                            3=>t('Флаги МПУ'),
                            4=>t('Флаги Украины'),
                            5=>t('Наклейки М'),
                            6=>t('Наклейки Меритократия'),
                            7=>t('Палатка'),
                            8=>t('Стол')
                          );
            return ($id) ? (isset($types[$id]) ? $types[$id] : false) : $types;
        }
}

?>
