<?

class attentions_peer extends db_peer_postgre
{
	protected $table_name = 'attentions';

	/**
	 * @return admin_complaint_peer
	 */
	public static function instance()
	{
		return parent::instance( 'attentions_peer' );
	}

        public function get_attention()
        {   
            $attentions=parent::get_list(array('hidden'=>false));
            foreach($attentions as $attention_id)
            {
                    $attention_hides=db_key::i()->get('attention_hides:'.$attention_id.':'.session::get_user_id());
                    if (!db_key::i()->exists('attention:'.date("md").':'.session::get_user_id()) && $attention_hides<3)
                            { 
                                    return parent::get_item($attention_id);
                            }
            }
        }
}