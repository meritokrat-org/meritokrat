<?

load::app('modules/admin/controller');
class admin_agitconvert_action extends frontend_controller
{
	public function execute()
	{
$data=db::get_rows("SELECT * FROM user_desktop WHERE information_banners !='a:0:{}' OR 
    information_publications !='a:0:{}' OR information_avtonumbers_photos !='a:0:{}'");
foreach($data as $d){
    $banners=unserialize($d['information_banners']);
    $publications=unserialize($d['information_publications']);
    $photos=unserialize($d['information_avtonumbers_photos']);
    print_R($photos);
    foreach ($banners as $b){
        db::exec("INSERT INTO agitation(salt,name,description,user_id,type) 
            VALUES ('".$b['salt']."','".$b['name']."','".$b['description']."',".$d['user_id'].",1)");
    }
    foreach ($publications as $p){
                    db::exec("INSERT INTO agitation(salt,name,description,user_id,type) 
            VALUES ('".$p['salt']."','".$p['name']."','".$p['description']."',".$d['user_id'].",2)");
    }
    foreach ($photos as $ph){
                    db::exec("INSERT INTO agitation(salt,name,description,user_id,type) 
            VALUES ('".$ph['salt']."','".$ph['name']."','".$ph['description']."',".$d['user_id'].",3)");
    }
}
            die('ok');
        }
}
