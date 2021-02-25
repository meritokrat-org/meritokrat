<? 
$arr=array('',
    array('misli','Мысли','blogs'),
    array('ludi','Люди','people'),
    array('soobsh','Сообщества','groups'),
    array('ideolog','Идеология','ideas'),
    array('opros','Опрос','polls'),
    array('favorites','Любимые авторы','people'),
    array('podii','События','events'),
    array('ppo','Ппо','ppo')
    ); 
?>

<div class="left" style="width: 265px">

    <?
    $total = 0;
    foreach($types as $t)
    {
        $total++;
        $num = count($$items[$t]);
        ?>
        <div class="left" style="width:265px;">
        <div class="column_head mt10" onclick="Application.ShowHide('<?=$arr[$t][0]?>')">
            <a href="/bookmarks?type=<?=$t?>" class="left"><?=t($arr[$t][1])?></a>
            <div class="right mt5 icoupicon <?=($total>3||$num==0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_on"></div>
            <div class="right mt5 icodownt <?=($total<=3&&$num!=0)?'hide':''?>" style="cursor: pointer;" id="<?=$arr[$t][0]?>_off"></div>
        </div>
        <?
        if($num)
        {
            ?>
            <div id="<?=$arr[$t][0]?>" class="<?=($total>3)?'hide':''?>">
                <? include 'partials/item_' . $t . '.php'; ?> 
            </div>
            <?
        }
        else
        {
            ?>
            <div id="<?=$arr[$t][0]?>" class="fs12 p10 hide" style="text-align:center;color:grey;">
                <?=t('Тут еще нет записей')?><br />
                <a href="/<?=$arr[$t][2]?>?bookmark=<?=$t?>"><?=t('Добавить')?></a>
            </div>
            <?
        }
        ?>
        </div>
        <?
    }
    ?>

</div>
<? $big = 1; ?>
<div class="left ml10" style="width: 470px;margin-top:10px;">

    <h1 class="column_head">
        <?=t($arr[$type][1])?>
        <div class="right">
            <a style="text-transform:none;" href="/<?=$arr[$type][2]?>?bookmark=<?=$type?>"><?=t('Добавить')?></a>
        </div>
    </h1>

    <? 
    if(count($$items[$type]))
    {
        include 'partials/item_' . $type . '.php'; 
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