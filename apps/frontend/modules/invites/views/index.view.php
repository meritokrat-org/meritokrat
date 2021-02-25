<?
$arr=array('',
    array('events','Приглашения на события','Посмотреть события'),
    array('groups','Приглашения в сообщества','Посмотреть сообщества'),
    array('polls','Приглашения на опрос','Посмотреть опросы'),
    array('ppo','Приглашения в парт. организации','Посмотреть партийные организации'),
    array('team','Приглашения в команду','Посмотреть команды'),
    array('projects', 'Приглашения в проекты', 'Запрошенння до проекту')
);
?>

<div class="left" style="width: 265px">

    <?
    $total = 0;
    foreach($types as $t)
    {
        $total++;
        $num = count($$items[$t]);
        if($t==3 && $num==0)
        {

        }
        else
        {
            ?>
            <div class="left" style="width:265px;">
                <div class="right mt15 mr10 <?=($total>3||$num==0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_on" onclick="Application.ShowHide('<?=$arr[$t][0]?>')"><img src="https://meritokrat.org/static/images/icons/up_icon.jpg" /></div>
                <div class="right mt15 mr10 <?=($total<=3&&$num!=0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_off" onclick="Application.ShowHide('<?=$arr[$t][0]?>')"><img src="https://meritokrat.org/static/images/icons/down_icon.jpg" /></div>
                <h1 class="column_head mt10">
                    <a href="/invites?type=<?=$t?>"><?=t($arr[$t][1])?></a>
                </h1>
                <?
                if($num)
                {
                    ?>
                    <div id="<?=$arr[$t][0]?>" class="<?=($total>3)?'hide':''?>">
                        <? include 'partials/part_' . $t . '.php'; ?>
                    </div>
                    <?
                }
                else
                {
                    ?>
                    <div id="<?=$arr[$t][0]?>" class="fs12 p10 hide" style="text-align:center;color:grey;">
                        <?=t('Тут еще нет записей')?><br />
                        <a href="/<?=$arr[$t][0]?>"><?=t($arr[$t][2])?></a>
                    </div>
                    <?
                }
                ?>
            </div>
            <?
        }
    }
    ?>

</div>
<? $big = 1; ?>
<div class="left ml10" style="width: 470px;margin-top:10px;">

    <h1 class="column_head">
        <?=t($arr[$type][1])?>
        <a class="right" href="/invites"><?=t('Все')?></a>
    </h1>

    <?
    if(count($$items[$type]))
    {
        include 'partials/part_' . $type . '.php';
    }
    else
    {
        ?>
        <div class="fs12 mt15" style="text-align:center;color:grey;"><?=t('Тут еще нет записей')?></div>
        <?
    }
    ?>

    <div class="right pager"><?=pager_helper::get_short($pager)?></div>

</div>