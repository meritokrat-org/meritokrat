<?php

load::app('modules/admin/controller');

class admin_election_action extends admin_controller
{
	protected $authorized_access = false;
	protected $credentials = array();
	
	public function execute()
	{
		$act = request::get_string('act');
		
		if(in_array($act, array('save', 'get', 'get_formatted', 'get_script')))
		{
			$this->set_renderer('ajax');
			return $this->json['success'] = $this->$act();
		}
		
		$this->election_summa = 0;
		if(db_key::i()->exists('election.summa'))
			$this->election_summa = db_key::i()->get('election.summa');
		
		$this->history = array();
		if(db_key::i()->exists('election.history'))
			$this->history = unserialize(db_key::i()->get('election.history'));
	}
	
	public function save()
	{
		if( ! session::has_credential('admin'))
		{
			$this->redirect('/admin/election');
			return false;
		}
		
		db_key::i()->set('election.summa', request::get_int('summa'));
		
		$history = array();
		if(db_key::i()->exists('election.history'))
			$history = unserialize(db_key::i()->get('election.history'));
		
		$history[] = array(
			'user_id' => session::get_user_id(),
			'time' => time()
		);
		
		db_key::i()->set('election.history', serialize($history));
		
		return true;
	}
	
	public function get()
	{
		$this->json['summa'] = 0;
		if(db_key::i()->exists('election.summa'))
			$this->json['summa'] = db_key::i()->get('election.summa');
		
		return true;
	}
	
	public function get_formatted()
	{	
		$summa = 0;
		if(db_key::i()->exists('election.summa'))
			$summa = db_key::i()->get('election.summa');
		
		$this->json['needed'] = number_format((2204000-$summa), 0, ",", " ");
		$this->json['collected'] = number_format($summa, 0, ",", " ");
		
		return true;
	}
	
	public function get_script()
	{
		$this->set_renderer('html');
		$this->set_layout(null);
		$this->set_template('election_script');
		
		header("Content-type: text/javascript");
		
		$summa = 0;
		if(db_key::i()->exists('election.summa'))
			$summa = db_key::i()->get('election.summa');
		
		$this->needed = number_format((2204000-$summa), 0, ",", " ");
		$this->collected = number_format($summa, 0, ",", " ");
	}
}

?>
