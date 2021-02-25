<?php
class user_desktop_help_peer extends db_peer_postgre
{
	protected $table_name = 'user_desktop_help';
	protected $primary_key = 'id';

	/**
	 * @return user_data_peer
	 */
	public static function instance()
	{
		return parent::instance( 'user_desktop_help_peer' );
	}
        public static function getHelpTypes() {
            load::model('user/user_desktop_help_types');
            $help_types_id = user_desktop_help_types_peer::instance()->get_list();
            foreach($help_types_id as $id=>$value) {
                $tmp = user_desktop_help_types_peer::instance ()->get_item($value);
                $help_types[$value] = $tmp['name'];
            }
            return $help_types;
        }


}
?>