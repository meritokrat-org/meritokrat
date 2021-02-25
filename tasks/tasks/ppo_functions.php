<?
class ppo_functions_task extends shell_task
{
	public function execute()
	{
            
		load::model('user/user_desktop');
/*
		$ppo_rows = db::get_rows("SELECT id, category, region_id, city_id FROM ppo");
		foreach($ppo_rows as $ppo) $ppos[$ppo['id']]=$ppo;
*/              

                //Автоматично присвоювати функцію "Член Політради" усім Головам РПО
                $leaders_rpo=db::get_rows("SELECT user_desktop.functions,user_desktop.user_id FROM user_desktop WHERE user_desktop.user_id in
                                (SELECT ppo_members.user_id
                                FROM ppo_members
                                left JOIN ppo ON ppo_members.group_id=ppo.id
                                WHERE ppo.id is not null and ppo.category=3 AND ppo_members.function=1
                                AND ppo_members.user_id not in 
                                (SELECT user_id FROM user_desktop WHERE functions && '{1}'))");
                /*foreach($leaders_rpo as $leader_rpo)
                {
                            $functions_insert=str_replace('}',',1}',$leader_rpo['functions']);
                            //echo $user_functions_insert.'\n';
                            user_desktop_peer::instance()->update(array(
                                'user_id' => $leader_rpo['user_id'],
                                'functions' => $functions_insert
                            ));
                }*/
              
                
                //COMMON PART
                foreach (array(1,2) as $function)
                {
                    foreach (array(1,2,3) as $category)
                    {

                        $id_function='1'.$function.$category;//хитросплетенно
                        
                        $ppo_members_insert = db::get_cols("SELECT ppo_members.user_id
                                FROM ppo_members
                                left JOIN ppo ON ppo_members.group_id=ppo.id
                                WHERE ppo.id is not null AND ppo_members.function=".$function." AND ppo.category=".$category."
                                AND ppo_members.user_id not in(
                                    SELECT user_id FROM user_desktop WHERE functions && '{".$id_function."}'
                                )");
                                
                        if (count((array)$ppo_members_insert))
                        {
                            foreach ($ppo_members_insert as $user_id)
                            {
                               
                                if ($user_desktop_data=user_desktop_peer::instance()->get_item($user_id))
                                {
                                    $user_functions_insert=$user_desktop_data['functions'];
                                    //die($user_functions_insert);

                                    if ($user_functions_insert=='{}')
                                            $user_functions_insert='{'.$id_function.'}';
                                    else
                                            $user_functions_insert=str_replace('}',','.$id_function.'}',$user_functions_insert);
                                    //echo $user_functions_insert.'\n';
                                    user_desktop_peer::instance()->update(array(
                                        'user_id' => $user_id,
                                        'functions' => $user_functions_insert
                                    ));
                                }
                                else user_desktop_peer::instance()->insert(array(
                                    'user_id' => $user_id,
                                    'functions' => '{'.$id_function.'}'
                                ));
                            }
                        }
                        
                        
                        $user_functions_update = db::get_cols("
                                SELECT user_id FROM user_desktop WHERE functions && '{1".$function.$category."}'
                                AND user_id not in(
                                    SELECT ppo_members.user_id
                                    FROM ppo_members
                                    left JOIN ppo ON ppo_members.group_id=ppo.id
                                    WHERE ppo.id is not null AND ppo_members.function=".$function." AND ppo.category=".$category."
                                )");
                        
                        if (count((array)$user_functions_update))
                        {
                            foreach ($user_functions_update as $user_id)
                            {
                                $user_functions=db::get_row("SELECT functions FROM user_desktop WHERE user_id=".$user_id);
                                
                                $user_functions=str_replace(array(','.$id_function,$id_function),array('',''), $user_functions['functions']);

                                user_desktop_peer::instance()->update(array(
                                    'user_id' => $user_id,
                                    'functions' => str_replace(array(',,',',}'),array(',','}'),$user_functions)
                                ));
                            }
                        }

                    }
                }
                
                /* OLD
		$ppo_members_rows = db::get_rows("SELECT ppo_members.user_id, ppo.id, ppo_members.function, ppo.category, ppo.region_id, ppo.city_id
                                FROM ppo_members
                                left JOIN ppo ON ppo_members.group_id=ppo.id
                                WHERE ppo.id is not null and ppo_members.function in (1,2)");
		
                foreach($ppo_members_rows as $ppo_member)
                {
                    $ppo_members_ids[]=$ppo_member['user_id'];
                    $ppo_members[$ppo_member['user_id']]=$ppo_member;
                }
                
                $user_functions = array();//db::get_cols("SELECT user_id FROM user_desktop WHERE
                                functions && '{111}'
                                OR functions && '{112}'
                                OR functions && '{113}'
                                OR functions && '{121}'
                                OR functions && '{122}'
                                OR functions && '{123}'");
                
                $ppo_members_insert=array_diff((array)$ppo_members_ids, (array)$user_functions);
                

                //var_dump($ppo_members_insert);
                if (count((array)$ppo_members_insert))
                {
                    foreach ($ppo_members_insert as $user_id)
                    {
                        if ($user_desktop_data=user_desktop_peer::instance()->get_item($user_id))
                        {
                            $user_functions_insert=db::get_row("SELECT functions FROM user_desktop WHERE user_id=".$user_id);

                            $id_function='1'.$ppo_members[$user_id]['function'].$ppo_members[$user_id]['category'];//хитросплетенно
                            //echo $user_id.'='.$user_functions_insert['functions'].'-';
                            $user_functions_insert=str_replace($replace,$replace2, $user_functions_insert['functions']);
                            //die($user_functions_insert);
                            
                            if ($user_functions_insert=='{}')
                                    $user_functions_insert='{'.$id_function.'}';
                            else
                                    $user_functions_insert=str_replace('}',','.$id_function.'}',$user_functions_insert);
                            //echo $user_functions_insert.'\n';
                            user_desktop_peer::instance()->update(array(
                                'user_id' => $user_id,
                                'functions' => $user_functions_insert
                            ));
                        }
                        else user_desktop_peer::instance()->insert(array(
                            'user_id' => $user_id,
                            'functions' => '{'.$id_function.'}'
                        ));
                    }
                }
               //die();
                $user_functions_update=array_diff((array)$user_functions, (array)$ppo_members_ids);
		if (count((array)$user_functions_update))
                {
                    foreach ($user_functions_update as $user_id)
                    {
                        $replace=array(111,112,113,121,122,123);
                        $replace2=array('','','','','','','');
                        $user_functions=db::get_row("SELECT functions FROM user_desktop WHERE user_id=".$user_id);
                        //echo $user_functions.'-';
                        $user_functions=str_replace($replace,$replace2, $user_functions['functions']);
                        //echo $user_functions.'|';
                        user_desktop_peer::instance()->update(array(
                            'user_id' => $user_id,
                            'functions' => str_replace(array(',,',',}'),array(',','}'),$user_functions)
                        ));
                        //echo str_replace(array(',,',',}'),array(',','}'),$user_functions);
                    }
                }
                */   
                
                
                
                
           /*     Удалить мусор
                $user_functions = db::get_cols("SELECT user_id FROM user_desktop WHERE
                                functions && '{111}'
                                OR functions && '{112}'
                                OR functions && '{113}'
                                OR functions && '{121}'
                                OR functions && '{122}'
                                OR functions && '{123}'");
                
                $user_functions_update=$user_functions;
		if (count((array)$user_functions_update))
                {
                    foreach ($user_functions_update as $user_id)
                    {
                        $replace=array(111,112,113,121,122,123);
                        $replace2=array('','','','','','','');
                        $user_functions=db::get_row("SELECT functions FROM user_desktop WHERE user_id=".$user_id);
                        //echo $user_functions.'-';
                        $user_functions=str_replace($replace,$replace2, $user_functions['functions']);
                        $user_functions=str_replace(',,,,,,,,',',', $user_functions);
                        $user_functions=str_replace(',,,,,,',',', $user_functions);
                        $user_functions=str_replace(',,,,',',', $user_functions);
                        $user_functions=str_replace(',,',',', $user_functions);
                        $user_functions=str_replace(',,',',', $user_functions);
                        $user_functions=str_replace(',,',',', $user_functions);
                        $user_functions=str_replace(',,',',', $user_functions);
                        $user_functions=str_replace(',}','}',$user_functions);
                        $user_functions=str_replace('{,','{',$user_functions);
                        //echo $user_functions.'|';
                        user_desktop_peer::instance()->update(array(
                            'user_id' => $user_id,
                            'functions' => $user_functions
                        ));
                        //echo str_replace(array(',,',',}'),array(',','}'),$user_functions);
                    }
                }
                
                die();
             */   
	}
}