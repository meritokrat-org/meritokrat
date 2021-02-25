<?
class admin_cron_rating_action extends frontend_controller
{
        const RECORDS_STEP = 50;
        public function execute()
        {
            load::model('photo/photo');
            load::model('photo/photo_albums');
            load::view_helper('photo');
            load::action_helper('pager');
            



            if(session::get_user_id()!=5968) die();
            if(request::get('download')) {
                load::system('storage/storage_simple');
//                $albums = array(141,142,105,97,84,83,82);
                $albums = array(105);
                foreach ($albums as $key) {
                $photos = photo_peer::instance()->get_album($key);
                if($photos)
                    foreach ($photos as $k => $v) {
                        $item = photo_peer::instance()->get_item($v);
                        $storage = new storage_simple();
                        print_r($storage->get_path('photoalbum/' . $item['id'] . '-' . $item['salt'].'.jpg'). ' ---> ');
                        print_r('/var/www/tmp/'.$key.'/'.$item['id'].'-'.$item['salt'].'.jpg.....   ');
                        if(copy($storage->get_path('photoalbum/' . $item['id'] . '-' . $item['salt'].'.jpg'),'/var/www/tmp/'.$key.'/'.$item['id'].'-'.$item['salt'].'.jpg')) echo "<b>OK</b><br><br>";
                        
                        else "<b>FAIL</b><br><br>";

                    }
                }
                exit;
            }
            
            if(request::get('clear_redis')) {
                db_key::i()->delete("cron_rating_id");
                exit;
            }
            
            load::model('user/user_auth');
            load::model('user/user_data');
            load::model('user/user_desktop');
            
            load::view_helper('user');
            load::action_helper('rating',false);
            
            rating_helper::init();
            
            $counter = 0;
            $current_id = (db_key::i()->exists("cron_rating_id")) ? db_key::i ()->get ("cron_rating_id") : 0;
            $all_records=1;
            $mpus = db::get_cols("SELECT id FROM user_auth WHERE status=20 OR (credentials LIKE '%admin%') ORDER BY id");

            foreach ($mpus as $k => $uid) {
                
                if($uid<=$current_id) 
                    continue;
                else 
                    $all_records=0;
                
                if($all_records) {
                    db_key::i()->delete ("cron_rating_id");
                    exit;
                }
                
                $user_desktop = user_desktop_peer::instance()->get_item($uid);
                $user_data = user_data_peer::instance()->get_item($uid);
                $user_auth = user_auth_peer::instance()->get_item($uid);

                if($user_auth && $user_data && $user_desktop) {
                    /////////////////////AVATAR WITH @M@
                    $avatarm = unserialize($user_desktop['avatarm']);
                    if($avatarm) { 
                        foreach ($avatarm as $k => $v) if(!$v) unset($avatarm[$k]);
                        $data['avatarmRat'] = count($avatarm);
                    }
                    else $data['avatarmRat'] = 0;

                    ////////////////////AUTONUMBERS
                    $photo_data = unserialize($user_desktop['information_avtonumbers_photos']);
                    $autonumbers = array();
                    if($photo_data) {
                        foreach ($photo_data as $k => $v) {
                            if(!$v) unset($photo_data[$k]);
                            if(intval($v['type'])==0) $autonumbers[] = $v;
                        }
                        $data['autonumbersRat'] = count($autonumbers);
                    }
                    else 
                        $data['autonumbersRat'] = 0;
                    

                    ///////////////////MAGNETS
                    $img_data = unserialize($user_desktop['information_avtonumbers_photos']);
                    $magnets = array();
                    if($img_data) {
                        foreach ($img_data as $k => $v) {
                            if($v) unset($img_data[$k]);
                            if(intval($v['type'])==1) $magnets[] = $v;
                        }
                        $data['magnetsRat'] = count($magnets);
                    }
                    else 
                        $data['magnetsRat'] = 0;
                    ///////////////////BANNERS

                    $banners = unserialize($user_desktop['information_banners']);
                    $data['bannerRat'] = 0;
                    if(is_array($banners) && count($banners)>0)
                        $data['bannerRat'] = count($banners);

                    /////////////////SIGNATURES
                    
                    $data['signatures'] = db::get_scalar("SELECT SUM(fact) FROM user_desktop_signatures_fact WHERE user_id=:uid",array('uid'=>$uid));
                    if(!$data['signatures']) $data['signatures'] = 0;
                    
                    //////////////////PUBLICATIONS
                    $publicationsData = unserialize($user_desktop['information_publications']);
                    $data['publications'] = 0;
                    if(is_array($publicationsData) && count($publicationsData)>0)
                        $data['publications'] = count($publicationsData);


                    //////////////////SPEACHES
                    $data['speach_members'] = db::get_scalar("SELECT sum(member_count) FROM user_desktop_event WHERE user_id=:uid AND (part=2 OR part=0)",array('uid'=>$uid));
                    if(!$data['speach_members']) $data['speach_members']=0;

                    ///////////////////TENT AGITATION
                    $tent_data = unserialize($user_desktop['information_tent']);
                    $hours_in_tent = 0;
                    if($tent_data)
                        foreach ($tent_data as $k => $v) 
                            $data['hours_in_tent'] += intval($v['hours']);

                    ////////////////INVITED  
                    $data['guests'] = db::get_scalar("SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status<5",array('invBy'=>$uid));
                    $data['supporters'] = db::get_scalar("SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=5 AND status<10",array('invBy'=>$uid));
                    $data['meritokrats'] = db::get_scalar("SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=10 AND status<20",array('invBy'=>$uid));
                    $data['mpu_members'] = db::get_scalar("SELECT count(id) FROM user_auth WHERE (((invited_by=:invBy OR recomended_by=:invBy) AND active=true) OR (offline=:invBy)) AND status>=20",array('invBy'=>$uid));


                    //////////////////PAYMENTS
                    $data['mem'] = db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=1 AND user_id=:uid AND del=0 AND approve!=0",array('uid'=>$uid));
                    $data['reg'] = db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=2 AND user_id=:uid AND del=0 AND approve!=0",array('uid'=>$uid));
                    $data['cha'] = db::get_scalar("SELECT SUM(summ) FROM user_payments WHERE type=3 AND user_id=:uid AND del=0 AND approve!=0",array('uid'=>$uid));

                    
                    ///////////////////GET POINTS
                    $data['point_count'] = db::get_scalar("SELECT SUM(points) FROM user_rating_admin_points WHERE user_id=:uid",array('uid'=>$uid));
                    
                    
                    
                    $insert_data = array(
                        'id'=>$uid,
                        'avatarm'=>$data['avatarmRat'] ? $data['avatarmRat'] : 0,
                        'autonumber'=>$data['autonumbersRat'] ? $data['autonumbersRat'] : 0,
                        'magnet'=>$data['magnetsRat'] ? $data['magnetsRat'] : 0,
                        'banners'=>$data['bannerRat'] ? $data['bannerRat'] : 0,
                        'signatures'=>$data['signatures'] ? $data['signatures'] : 0,
                        'publications'=>$data['publications']  ? $data['publications'] : 0,
                        'speach'=>$data['speach_members'] ? $data['speach_members'] : 0,
                        'tent'=>$data['hours_in_tent'] ? $data['hours_in_tent'] : 0,
                        'guest'=>$data['guests'] ? $data['guests'] : 0,
                        'supporters'=>$data['supporters'] ? $data['supporters'] : 0,
                        'meritokrats'=>$data['meritokrats'] ? $data['meritokrats'] : 0,
                        'mpu_members'=>$data['mpu_members'] ? $data['mpu_members'] : 0,
                        'charitable'=>$data['cha'] ? $data['cha'] : 0,
                        'membership'=>$data['mem'] ? $data['mem'] : 0,
                        'regular'=>$data['reg'] ? $data['reg'] : 0
                    );
                    
                    $insId = user_rating_peer::instance()->get_item($uid);
                    if($insId) {
                        echo "<h1>------------UPDATING---------------</h1>";
                        user_rating_peer::instance ()->update ($insert_data);
                    }
                    else {
                        echo "<h1>------------INSERTING---------------</h1>";
                        $newId = user_rating_peer::instance ()->insert ($insert_data);
                    }
                    
                    $full = rating_helper::calculate_by_user($uid);
                    if($full) user_rating_peer::instance ()->update(array('id'=>$uid,'rating'=>(floatval($full['full_rating'])+floatval($data['point_count'])),'regional_ratio'=>$full['regional_ratio']));
                    
                    echo "<pre>";
                    echo "<h3>ID=".$uid."</h3>";
                    print_r($insert_data);
                    echo "</pre>";
                    unset($data);
                    unset($insert_data);
                    
                    $counter++;
                    if($counter==self::RECORDS_STEP) {
                        db_key::i()->set("cron_rating_id", $uid);
                        echo "<br/>--------------------------------<br/>";
                        var_dump(db_key::i()->get('cron_rating_id'));
                        exit();
                    }
                    
                    

                }
            }
            
        }
}
?>
