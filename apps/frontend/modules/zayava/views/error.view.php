<div style="width:760px" class="left ">
    <div class="mt10 mb10">
	<h1 class="column_head">Заява про вступ до мерiтократичної партiї України</h1>
    </div>
    <div style="width:750px" class="mt15 ml15 fs14">

            <p align="center">
                <b>Вибачте, для подання заяви Ви повиннi заповнити наступнi поля у профiлi:
                <span class="cbrown">
                <?
                $n = array();
                foreach($needed as $k=>$v)
                {
                    if($k!='phone')
                    {
                        if(!$user[$k])
                        {
                            $n[] = $v;
                        }
                    }
                    else
                    {
                        if(!$user['phone'] && !$user['mobile'] && !$user['home_phone'])
                        {
                            $n[] = $needed['phone'];
                        }
                    }
                }
                echo implode(', ',$n);
                ?>
                </span>
                </b>
            </p>
            <p align="center"><b><a href="/profile">Перейти до профiля</a></b></p>
                  
    </div>
</div>