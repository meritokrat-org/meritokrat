<?php

load::app('modules/election/controller');
load::model('geo');
load::model('user/user_voter');
load::model('user/user_data');

class election_index_action extends election_controller
{
	public function execute()
	{
		parent::execute();
		
		$this->table = array();
		
		if(request::get_int('city_id') || request::get_string('filter') != '')
		{
			$this->people_list();
			return;
		}
		elseif(request::get_int('region_id'))
		{
			$this->locations = geo_peer::instance()->get_cities(request::get_int('region_id'));
			$location_key = 'city_id';
		}
		else
		{
			$this->locations = geo_peer::instance()->get_regions(1);
			$location_key = 'region_id';
		}
		
		$this->frame = 'locations';
		
		$this->location_key = $location_key;
		
		$this->user_voter_list = user_voter_peer::instance()->get_list();
		
		foreach($this->user_voter_list as $id)
		{
			$user_voter_item = user_voter_peer::instance()->get_item($id);
			
			$user_data_item = user_data_peer::instance()->get_item($user_voter_item['user_id']);
			
			$location_id = $user_data_item[$location_key];
			
			$this->check_table($location_id, $user_voter_item);
			$this->check_table(0, $user_voter_item);
		}
	}
	
	private function people_list()
	{
		$this->frame = 'people';
		
		if(request::get_int('city_id'))
		{
			$location_id = request::get_int('city_id');
			$this->location_key = 'city_id';
		}
		elseif(request::get_int('region_id'))
		{
			$location_id = request::get_int('region_id');
			$this->location_key = 'region_id';
		}
		
		$this->user_voter_list = user_voter_peer::instance()->get_list();
		
		foreach($this->user_voter_list as $id)
		{
			$user_voter_item = user_voter_peer::instance()->get_item($id);
			
			$user_data_item = user_data_peer::instance()->get_item($user_voter_item['user_id']);
			if($user_data_item[$this->location_key] != $location_id)
				continue;
			
			if(request::get_string('filter') != '')
			{
				if($user_voter_item['admin_data'][request::get_string('filter')] < 1)
				{
					continue;
				}
			}
			
			$this->check_table($user_voter_item['user_id'], $user_voter_item);
		}
	}
	
	private function check_table($id, $user_voter)
	{
		if( ! isset($this->table[$id]))
			$this->table[$id] = array();

		if( ! isset($this->table[$id]['willVote']))
			$this->table[$id]['willVote'] = 0;
		if($user_voter['admin_data']['willVote'] > 0)
		{
			$this->table[$id]['willVote']++;
		}
		
		if( ! isset($this->table[$id]['agitated']))
			$this->table[$id]['agitated'] = 0;
		foreach($user_voter['informator'] as $informator)
		{
			if($informator['contacts'][count($informator['contacts'])-1]['result'] == 1)
			{
				$this->table[$id]['agitated']++;
			}
		}
		
		if( ! isset($this->table[$id]['financialSupport']))
		{
			$this->table[$id]['financialSupport'] = 0;
			$this->table[$id]['countFinancialSupport'] = 0;
		}
		if($user_voter['admin_data']['financialSupport'] > 0)
		{
			$this->table[$id]['financialSupport']++;
			$this->table[$id]['countFinancialSupport'] += $user_voter['admin_data']['countFinancialSupport'];
		}
		
		if( ! isset($this->table[$id]['agitateMyFamily']))
			$this->table[$id]['agitateMyFamily'] = 0;
		if($user_voter['admin_data']['agitateMyFamily'] > 0)
		{
			$this->table[$id]['agitateMyFamily']++;
		}
		
		if( ! isset($this->table[$id]['agitateInInternet']))
			$this->table[$id]['agitateInInternet'] = 0;
		if($user_voter['admin_data']['agitateInInternet'] > 0)
		{
			$this->table[$id]['agitateInInternet']++;
		}
		
		if( ! isset($this->table[$id]['agitateOnStreet']))
			$this->table[$id]['agitateOnStreet'] = 0;
		if($user_voter['admin_data']['agitateOnStreet'] > 0)
		{
			$this->table[$id]['agitateOnStreet']++;
		}
		
		if( ! isset($this->table[$id]['volunteerInKiev']))
			$this->table[$id]['volunteerInKiev'] = 0;
		if($user_voter['admin_data']['volunteerInKiev'] > 0)
		{
			$this->table[$id]['volunteerInKiev']++;
		}
		
		if( ! isset($this->table[$id]['volunteerInRegion']))
			$this->table[$id]['volunteerInRegion'] = 0;
		if($user_voter['admin_data']['volunteerInRegion'] > 0)
		{
			$this->table[$id]['volunteerInRegion']++;
		}
		
		if( ! isset($this->table[$id]['wantRunSingle']))
			$this->table[$id]['wantRunSingle'] = 0;
		if($user_voter['admin_data']['wantRunSingle'] > 0)
		{
			$this->table[$id]['wantRunSingle']++;
		}
		
		if( ! isset($this->table[$id]['wantRunByList']))
			$this->table[$id]['wantRunByList'] = 0;
		if($user_voter['admin_data']['wantRunByList'] > 0)
		{
			$this->table[$id]['wantRunByList']++;
		}
		
		if( ! isset($this->table[$id]['wantToBeObserver']))
			$this->table[$id]['wantToBeObserver'] = 0;
		if($user_voter['admin_data']['wantToBeObserver'] > 0)
		{
			$this->table[$id]['wantToBeObserver']++;
		}
		
		if( ! isset($this->table[$id]['wantToBeCommissioner']))
			$this->table[$id]['wantToBeCommissioner'] = 0;
		if($user_voter['admin_data']['wantToBeCommissioner'] > 0)
		{
			$this->table[$id]['wantToBeCommissioner']++;
		}
		
		if( ! isset($this->table[$id]['wantToBeLawyer']))
			$this->table[$id]['wantToBeLawyer'] = 0;
		if($user_voter['admin_data']['wantToBeLawyer'] > 0)
		{
			$this->table[$id]['wantToBeLawyer']++;
		}
	}
}

?>
