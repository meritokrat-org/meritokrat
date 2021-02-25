<?
class user_education_peer extends db_peer_postgre
{
	protected $table_name = 'user_education';
	protected $primary_key = 'user_id';
        protected $lang_cols=array("midle_edu_country","midle_edu_city",
            "midle_edu_name","smidle_edu_country","smidle_edu_city","smidle_edu_name",
            "major_edu_country","major_edu_city","major_edu_name","major_edu_faculty","major_edu_department",
            "additional_edu_country","additional_edu_city","additional_edu_name","additional_edu_faculty",
            "additional_edu_department","another_edu");

	/**
	 * @return user_data_peer
	 */
	public static function instance()
	{
                load::action_helper('lang',false);
		return parent::instance('user_education_peer');
	}

	public function update( $data, $keys = null )
	{
		parent::update(lang_helper::set_lang_cols($this->lang_cols,$data), $keys);
	}
        
	public function insert($data, $ignore_duplicate = false)
	{
		$id = parent::insert($data, $ignore_duplicate);
		return $id;
	}

	public static function get_forms()
	{
		return array(
                    '1'=>t('дневная'),
                    '2'=>t('вечерняя'),
                    '3'=>t('заочная')
                    );
	}

	public static function get_form( $id )
	{
		$list = self::get_forms();
		return $list[$id];
	}

	public static function get_statuces()
	{
		return array(
                    "1"=>t('Студент'),
                    "2"=>t('Студент (бакалавр)'),
                    "3"=>t('Студент (магистр)'),
                    "4"=>t('Выпускник (бакалавр)'),
                    "5"=>t('Выпускник (специалист)'),
                    "6"=>t('Выпускник (магистр)'),
                    "7"=>t('Аспирант'),
                    "8"=>t('Кандидат наук'),
                    "9"=>t('Доктор наук')
                    );
	}

	public static function get_status( $id )
	{
		$list = self::get_statuces();
		return $list[$id];
	}
        
        public function get_item($id,$session='language'){ 
            $data=parent::get_item($id); 
            return lang_helper::get_lang_cols($this->lang_cols,$data,$session);
        }
        
}
