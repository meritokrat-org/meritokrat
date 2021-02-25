<?

load::app('modules/pporeestr/controller');

class pporeestr_index_action extends reestr_controller {

	public function execute() {
		$this->regions = ppo_peer::instance()->get_ppo_regions();
		load::model('geo');
		foreach ($_GET as $param => $value) {
			switch ($param) {
				case 'dovidsdate':
					if ($value == 1)
						$sqladd.=" AND dovidsdate>0";
					elseif ($value == 2)
						$sqladd.=" AND (dovidsdate=0 OR dovidsdate IS NULL)";
					break;
				case 'svidvig':
					if ($value == 1)
						$sqladd.=" AND svidvig>0";
					elseif ($value == 2)
						$sqladd.=" AND (svidvig=0 OR svidvig IS NULL)";
					break;
				case 'svidvruch':
					if ($value == 1)
						$sqladd.=" AND svidvruch>0";
					elseif ($value == 2)
						$sqladd.=" AND (svidvruch=0 OR svidvruch IS NULL)";
					break;
				case 'zayava':
					if ($value == 1)
						$sqladd.=" AND zayava>0";
					elseif ($value == 2)
						$sqladd.=" AND (zayava=0 OR zayava IS NULL)";
					break;
				case 'vkl':
					if ($value == 1)
						$sqladd.=" AND vklnumber!='' AND vkldate>0";
					elseif ($value == 2)
						$sqladd.=" AND ((vklnumber='' OR vklnumber IS NULL) OR vkldate=0)";
					break;
			}
		}
		
		foreach ($this->regions as $region) {
			if($this->region_id)
				$region['region_id'] = $this->region_id;
                        if($this->city_id){
                        $city_add=" AND city_id=".$this->city_id;
			$sqladd.=$city_add;
                        }
			$rpo = ppo_peer::instance()->get_by_region($region['region_id'], false, 3, " ORDER by region_id");
			$reg = geo_peer::instance()->get_region($region['region_id']);
			if ($rpo[0]['id'] > 0)
				$rpo_data = ppo_peer::instance()->get_item($rpo[0]['id']);
			else
				$rpo_data = array();
			$ppo[] = array_merge($rpo_data, array("id" => $rpo[0]['id'], "title" => $reg['name_' . translate::get_lang()],
					"mpocnt" => db::get_scalar("SELECT count(*) FROM ppo WHERE region_id=" . $region['region_id'] .$city_add. "
											AND category=2 AND active=1"), "ppocnt" => db::get_scalar("SELECT count(*) 
													FROM ppo WHERE region_id=" . $region['region_id'] . "
											AND category=1 AND active=1" . $sqladd), "region_id" => $region['region_id'], "category" => 3));
			$citys = db::get_rows("SELECT city_id FROM ppo WHERE region_id=" . $region['region_id'] . " 
										AND active=1 $sqladd GROUP by city_id ORDER by city_id");
			foreach ($citys as $c) {
				$mpo = db::get_row("SELECT * FROM ppo WHERE region_id=" . $region['region_id'] . "
											 and city_id=" . $c['city_id'] . " AND category=2 AND active=1");
				if ($mpo['id'])
					$ppo[] = $mpo['id'];
				$ppos = ppo_peer::instance()->get_children(array('city_id' => $c['city_id']), 2, 1, $sqladd);
				foreach ($ppos as $pp) {
                    $ppo[] = $pp['id'];
                }
			}
			if(!$this->allList)
				break;
		}
		$this->list = $ppo;
	}

}