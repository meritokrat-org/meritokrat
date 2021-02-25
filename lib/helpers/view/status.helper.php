<?

load::view_helper('tag', true);

class status_helper
{
    const MERITOCRAT = 10;
    const MPU_MEMBER = 20;
    const CANDIDATE = 15;

    public static function get_status($uid) {
        $uData = user_auth_peer::instance()->get_item($uid);
        return ($uData) ? $uData['status'] : false;
    }
    public static function check_status($uid,$check_val=0) 
    {
            $stat_val = self::get_status($uid);
            return ($stat_val>=$check_val) ? true : false;
    }
    public static function is_polit_council($uid) {
        $funct = db::get_scalar("SELECT user_id FROM user_desktop WHERE functions && '{1}' and user_id=".$uid);
        $member = db::get_scalar("SELECT user_id FROM groups_members WHERE group_id=221 and user_id=".$uid);
        return ($funct|$member) ? true : false;
    }
    public static function can_M_recommend($uid) {
        $status = self::get_status($uid);
        return ($status>=10) ? true : false;
    }
    public static function can_PM_recommend($uData) {
        load::model('user/user_desktop');
        $status = self::check_status($uData['user_id'],self::MPU_MEMBER);
        $pcouncil = self::is_polit_council($uData['user_id']);
        $rc = in_array($uData['city_id'], user_desktop_peer::instance()->is_raion_coordinator($uData['user_id'],true));
        $regc = in_array($uData['region_id'], user_desktop_peer::instance()->is_regional_coordinator($uData['user_id'],true));
        return ($status|$pcouncil|$rc|$regc) ? true : false;
    }
    public static function can_ex_recommend($uData,$rData) {
        load::model('user/user_desktop');
        $pcouncil = self::is_polit_council($uData['user_id']);
//        if(session::get_user_id()==5968) {
//            echo "<br>------Ext----<br/>";
//            echo 'politcouncil=>';
//            var_dump($pcouncil);
//            echo '<br>';
//            echo 'rayon_id='.$uData['city_id'].'<br>';
//            echo 'rayon_coord=>';
//            var_dump(user_desktop_peer::instance()->is_raion_coordinator($uData['user_id'],true));
//            echo '<br>';
//            echo 'rayon_id='.$uData['region_id'].'<br>';
//            echo 'regional_coord=>';
//            var_dump(user_desktop_peer::instance()->is_regional_coordinator($uData['user_id'],true));
//            echo '<br>';
//            echo "--------END------<br>";
//        }
        $rc = in_array($rData['city_id'], user_desktop_peer::instance()->is_raion_coordinator($uData['user_id'],true));
        $regc = in_array($rData['region_id'], user_desktop_peer::instance()->is_regional_coordinator($uData['user_id'],true));
        return ($pcouncil|$rc|$regc) ? true : false;
    }
    
    public static function offlineOwn($uid, $off_id) {
        return (in_array($off_id,db::get_cols("SELECT id FROM user_auth WHERE offline=:off",array('off'=>$uid))));
    }
    
    
}