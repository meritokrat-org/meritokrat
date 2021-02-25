<?

load::model('user/user_voter');

class results_people_action extends frontend_controller {

	protected $authorized_access = false;

	public function execute() {
		load::action_helper('pager');
		$bind = array();
		$value = request::get_int('val');
		$order = "user_id";
		if (request::get_int('meritokrat') == 1)
			$_REQUEST['status'] = 10;
		if (request::get_int('status')) {
			if (request::get_int('status') == 10)
				$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE status>=" . request::get_int('status') . ")";
			else
				$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE status=" . request::get_int('status') . ")";
		}elseif (request::get_int('status') && request::get_int('offline')) {
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE status=" . request::get_int('status') . " AND offline=1)";
		} elseif (request::get_int('expert') == 1) {
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE expert != '0' AND expert != '')";
		} elseif (request::get_int('famous')) {
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE famous=1)";
		} elseif (request::get_string('identification') == "check") {
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE identification=0)";
		} elseif (request::get('offline') == 'all')
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE offline > 0)";
		elseif (request::get_int('del'))
			$sqladd = "AND user_id IN(SELECT id FROM user_auth WHERE del != 0)";
		elseif (request::get_int('function'))
			$sqladd = "AND user_id IN(SELECT user_id FROM user_desktop WHERE functions && '{" . request::get_int('function') . "}')";
		elseif (request::get_int('region'))
			$sqladd = "AND user_id IN(SELECT user_id FROM user_data WHERE region_id=" . request::get_int('region') . ")";

		#if(request::get_int('function')==1)$_REQUEST['act']="";
		switch (request::get('act')) {
			case 'covers':
				$this->list = array();
				$this->covers = array();
				$list = user_voter_peer::instance()->get_list();
				foreach ($list as $id) {
					$item = user_voter_peer::instance()->get_item($id);
					if ($item['tasks_data']['coverInFacebook'] > 0) {
						$this->list[] = $item['user_id'];
						$this->covers[$item['user_id']] = $item['tasks_data']['linkCoverInFacebook'];
					}
				}
				break;
			case 'rec':
			case 'reci':
				/* if (request::get('act')=='reci') $add="AND created_user_id IN(SELECT id FROM user_auth 
				  WHERE id=user_recomendations.created_user_id AND active=true)";

				  $data=db::get_rows("SELECT user_id, count(*) AS count
				  FROM user_recomendations WHERE 1=1 $add $sqladd GROUP BY user_id
				  ORDER BY count DESC"); */
				if (request::get('act') == 'reci')
					$sqladd = " AND user_auth.active is TRUE";
				$data = db::get_rows("SELECT user_id,(SELECT count(*) 
         FROM user_auth WHERE (invited_by=user_recomendations.user_id or recomended_by=user_recomendations.user_id)$sqladd) AS count
         FROM user_recomendations GROUP BY user_id ORDER BY count DESC");

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$this->list[] = $user['user_id'];
					$mes[$user['user_id']]['link'] = "/profile/desktop?id=" . $user['user_id'] . "&tab=people&attracted=1";
				}
				$mes['name'] = "Залучили в \"М\" за рекомендацією";
				break;
			case 'ipc':
			case 'ippc':
			case 'ipec':
			case 'ipsc':
			case 'brh':
				$arr = array("ipc" => "information_people_private_count",
					"ippc" => "information_people_phone_count",
					"ipec" => "information_people_email_count",
					"ipsc" => "information_people_social_count",
					"brh" => "information_brochure_presented");
				$coll = $arr[request::get('act')];
				$data = db::get_rows("SELECT user_id,$coll
                FROM user_desktop 
                WHERE $coll>0 $sqladd
                ORDER BY $coll DESC");
				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user[$coll];
					$mes[$user['user_id']]['link'] = "/profile/desktop?id=" . $user['user_id'] . "&tab=info";
					$this->list[] = $user['user_id'];
				}
				$peopleifn = array("ipc" => t('Во время личных встреч'),
					"ipc" => t('Во время личных встреч'),
					"ippc" => t('Телефонными звонками'),
					"ipec" => t('Электронными письмами'),
					"ipsc" => t('В социальных сетях'));
				if (request::get('act') == 'brh')
					$mes['name'] = "Вручено";
				else
					$mes['name'] = "Поінформовано людей " . $peopleifn[request::get('act')];

				break;
			case 'ipall':
				$this->list = db::get_cols("SELECT 
                  user_id 
                  FROM user_desktop
                  WHERE
                  information_people_social_count>0 OR
                  information_people_email_count>0 OR
                  information_people_phone_count>0 OR
                  information_people_private_count>0 $sqladd");
				$arr = array();
				foreach ($this->list as $user_id) {
					$count = db::get_scalar("SELECT 
                         SUM(information_people_social_count)+
                         SUM(information_people_email_count)+
                         SUM(information_people_phone_count)+
                         SUM(information_people_private_count) 
                         FROM user_desktop WHERE user_id=" . $user_id . " $sqladd");
					if (intval($count) == 0)
						continue;
					$arr[$user_id] = $count;
					$mes[$user_id]['val'] = $count;
					$mes[$user_id]['link'] = "/profile/desktop?id=" . $user_id . "&tab=info";
				}
				arsort($arr);
				foreach ($arr as $ak => $a)
					$tlist[] = $ak;
				$this->list = $tlist;
				$mes['name'] = "Поінформовано людей " . t('Всего');
				break;

			case 'photo':
				$this->list = db::get_cols("SELECT 
                  information_avtonumbers_photos
                  FROM user_desktop
                  WHERE
                  information_avtonumbers_photos!='a:0:{}'");
				$this->set_template('photos');
				break;

			case 'male':
				$this->list = db::get_cols("SELECT user_id FROM user_data WHERE gender='m' $sqladd");
				break;
			case 'female':
				$this->list = db::get_cols("SELECT user_id FROM user_data WHERE gender='f' $sqladd");
				break;
			case 'polls':
				$data = db::get_rows('
                  SELECT user_id, count(*) AS count 
                  FROM polls WHERE hidden=0
                  ' . $add . ' ' . $sqladd . ' GROUP BY user_id ORDER BY count DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$mes[$user['user_id']]['link'] = "/polls-" . $user['user_id'];
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Создано опросов');
				break;
			case 'voites':
				$data = db::get_rows('
                  SELECT user_id, count(*) AS count 
                                    FROM polls_votes 
                                    GROUP BY user_id ORDER BY count DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Голосований в опросах');
				break;
			case 'vpoz':
				$mes['name'] = t('Позитивных оценок мыслей');
			case 'vneg':
				(request::get('act') == 'vpoz') ? $rate = 1 : $rate = -1;
				$data = db::get_rows('
                  SELECT user_id, count(*) AS count 
                  FROM rate_history
                                    WHERE type=1 AND rate=' . $rate . ' AND object_id
                                    NOT IN(SELECT id FROM blogs_posts WHERE visible=false)
                                    GROUP BY user_id ORDER BY count DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$this->list[] = $user['user_id'];
				}
				if ($mes['name'] == '')
					$mes['name'] = t('Негативных оценок мыслей');
				break;
			case 'blogs':
				$data = db::get_rows('
                  SELECT user_id, count(*) AS count 
                  FROM blogs_posts WHERE visible=true
                  ' . $add . ' ' . $sqladd . ' GROUP BY user_id ORDER BY count DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$mes[$user['user_id']]['link'] = "/blog-" . $user['user_id'];
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Мыслей и тем (в сообществах)');
				break;

			case 'com':
				$data = db::get_rows('
                  SELECT user_id, count(*) AS count 
                  FROM blogs_comments
                  ' . $add . ' ' . $sqladd . ' GROUP BY user_id ORDER BY count DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['count'];
					$mes[$user['user_id']]['link'] = "/profile/comments?id=" . $user['user_id'];
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Мыслей и тем (в сообществах)');
				break;

			case 'time':
				$data = db::get_rows('
                  SELECT all_time,user_id FROM user_sessions
                  ' . $add . ' ' . $sqladd . ' GROUP BY user_id,all_time ORDER BY all_time DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = intval($user['all_time'] / 3600) . " " . t('часов');
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Времени в сети');
				break;
				
			case "ntime":
				$today = time();
				$date_point = date("Y-m-d", mktime(0, 0, 0, date("m")-3, date("d"), date("Y")))." 00:00:00";
				
				$data = db::get_rows("SELECT user_id, time, time_out 
						FROM user_visits_log 
						WHERE time > '{$date_point}' {$sqladd} 
						ORDER BY time DESC");
				
				$list = array();
				foreach($data as $user)
				{
					$val = strtotime($user["time_out"]) - strtotime($user["time"]);
					$list[$user["user_id"]]["id"] = $user["user_id"];
					$list[$user["user_id"]]["val"] += $val;
				}
				
				uasort($list, array("results_people_action", "compare"));
				
				foreach($list as $item)
				{
					$tokens = explode(".", number_format($item["val"]/3600, 2));
					$minuts = intval($tokens[1]*60/100);
					$mes[$item["id"]]["val"] = "<a href=\"/results/person?id={$item["id"]}\" target=\"_blank\">".$tokens[0]." г. ".$minuts." хв.</a>";
					$this->list[] = $item["id"];
				}
				
				$mes['name'] = t('Времени в сети за последние 3 месяца');
				
				break;
			
			case "last_visit":
				
				$date_point = date("Y-m-d", mktime(0, 0, 0, date("m")-3, date("d"), date("Y")))." 00:00:00";
				
				$data = db::get_rows("SELECT user_id, COUNT(user_id) AS count 
						FROM user_visits_log 
						WHERE time > '{$date_point}' {$sqladd} 
						GROUP BY user_id 
						ORDER BY count DESC");
				
				foreach($data as $user)
				{
					$mes[$user['user_id']]['val'] = $user["count"];
					$this->list[] = $user['user_id'];
				}
				
				$mes['name'] = t('Кол-во посещений за последние 3 месяца');
				break;
			
			case 'visits':
				$data = db::get_rows('
                  SELECT all_visits,user_id FROM user_sessions
                  ' . $add . ' ' . $sqladd . ' GROUP BY user_id,all_visits ORDER BY all_visits DESC');

				foreach ($data as $user) {
					$mes[$user['user_id']]['val'] = $user['all_visits'];
					$this->list[] = $user['user_id'];
				}
				$mes['name'] = t('Кол-во посещений');
				break;

			case 'magnits':
			case 'avtonumbers':
			case 'naglother':
				$data = db::get_rows("SELECT user_id,
                  information_avtonumbers_photos
                  FROM user_desktop
                  WHERE
                  information_avtonumbers_photos!='a:0:{}'");

				foreach ($data as $user) {
					$naglyadka = unserialize($user['information_avtonumbers_photos']);
					$autcnt = 0;
					foreach ($naglyadka as $ng) {
						if (request::get('act') == 'avtonumbers' && !$ng['type'])
							$autcnt++;
						elseif (request::get('act') == 'magnits' && $ng['type'] == 1)
							$autcnt++;
						elseif (request::get('act') == 'naglother' && $ng['type'] == 2)
							$autcnt++;
					}
					if (!$autcnt)
						continue;
					$mes[$user['user_id']]['val'] = $autcnt;
					$mes[$user['user_id']]['link'] = "/profile/desktop?id=" . $user['user_id'] . "&tab=naglyadka";
					$alist[$user['user_id']] = $mes[$user['user_id']]['val'];
				}
				arsort($alist);
				foreach ($alist as $ak => $a)
					$this->list[] = $ak;
				if (request::get('act') == 'avtonumbers')
					$mes['name'] = t('Авторамки');
				else
					$mes['name'] = t('Разное');
				break;
			case 'avatarm':
				$mes = array();
				$this->list = array();
				$alist = array();
				$data = db::get_rows("SELECT user_id,
                  avatarm
                  FROM user_desktop
                  WHERE
                  avatarm!='a:0:{}' GROUP BY user_id,avatarm");
				$social = user_data_peer::get_contact_types();
				foreach ($data as $user) {
					$avatarm = unserialize($user['avatarm']);
					$avatarmcnt = 0;
					if ($avatarm[request::get_int('type')] == '' && request::get('type') != 'all')
						continue;
					foreach ($avatarm as $type => $ng) {
						if ($ng != '' && $type == request::get_int('type') && request::get('type') != 'all')
							$avatarmcnt++;
						else
						if ($ng != '' && request::get('type') == 'all')
							$avatarmcnt++;
					}
					if (!$avatarmcnt)
						continue;
					$mes[$user['user_id']]['val'] = $avatarmcnt;
					$mes[$user['user_id']]['link'] = "/profile/desktop?id=" . $user['user_id'] . "&tab=naglyadka";
					$alist[$user['user_id']] = $avatarmcnt;
				}
				arsort($alist);
				foreach ($alist as $ak => $a)
					$this->list[] = $ak;
				$mes['name'] = t('Аватарки с "М" в соцсети') . " " . $social[request::get_int('type')];
				break;
			default:
				$this->list = db::get_cols("SELECT user_id FROM user_desktop WHERE 1=1 $sqladd");
		}
		if (!is_array($this->list))
			$this->list = array();
		$this->pager = pager_helper::get_pager($this->list, request::get_int('page'), 50);
		$this->list = $this->pager->get_list();
		$this->mes = $mes;
	}
	
	public static function compare($a, $b)
	{
		if($a["val"] == $b["val"])
			return 0;
		
		return ($a["val"] > $b["val"]) ? -1 : 1;
	}

}