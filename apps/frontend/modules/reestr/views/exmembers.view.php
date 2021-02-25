<style>
    .exmembers {
        border-collapse: collapse;
    }
    .exmembers td,th {
        text-align: center;
        vertical-align: middle;
        border: 1px solid black;
    }
    .exmembers th {
        background: #913D3E;
        color: #FFCC66;
    }
</style>
<div class="sub_menu mt10 mb5" style="width:1000px">
    <a style="text-decoration:underline" href="/zayava/list"><?=t('Заявления')?></a>
    <b class="fs12"><a href='/reestr'><?=t('Члены МПУ')?></a></b>
    <?if(session::has_credential('admin')) {?>
        <?if(context::get_controller()->get_action()!='exmembers') {?>
            <a class="ml5" href='/reestr/exmembers'>
        <? } else {?>
            <b class="fs12">
        <? } ?>
        <?=t('Бывшие члены МПУ')?>
        <?if(context::get_controller()->get_action()!='exmembers') {?>
            </a>
        <? } else {?>
            </b>
        <? } ?>
    <? } ?>
<!--    <a class="ml10" style="text-decoration:underline" href="/reestr/payments"><?=t('Взносы')?></a>-->

    <? if(session::has_credential('admin')){ ?>
        <a class="right" style="text-decoration:underline" href="/reestr/search">*<?=t('Расширенный поиск')?></a>
    <? } ?>
    <? if(intval(db_key::i()->get('schanger'.session::get_user_id())) OR in_array(session::get_user_id(),array(2,5,29,1360,3949,5968))){ ?>
        <a class="right" style="text-decoration:underline" href="javascript:;" onclick="Application.doMembership()">*<?=t('Решение о принятии')?></a>
    <? } ?>
</div>
<h1 class="column_head mb10" style="background: url(/static/images/common/bg-header.jpg) repeat-x 0 -75px;height:21px">
    <?=t('Бывшие члены МПУ')?>
    <? if(session::has_credential('admin')){ ?>
    <a class="right" href="/admin">*<?=t('Админка')?></a>
    <? } ?>
</h1>
<table class="exmembers fs12">
    <tr>
        <th>
            №
        </th>
        <th>
            <?=t('Ф.И.О.')?>
        </th>
        <th>
            <?=t('Регион')?>
        </th>
        <th>
            <?=t('Порядок')?>
        </th>
        <th>
            <?=t('Причина')?>
        </th>
        <th>
            <?=t('Дата')?>
        </th>
        <th>
            <?=t('Номер решения')?>
        </th>
        <th>
            <?=t('Кем')?>
        </th>
				<th>
            <?=t('Заявление')?>
        </th>
    </tr>
    <?if($list) 
        foreach ($list as $key=>$id) {
            $item = user_membership_peer::instance()->get_item($id);
            if($item) $udata = user_data_peer::instance()->get_item($item['user_id']);
						
						load::model("user/zayava_termination");
						$statement = user_zayava_termination_peer::instance()->get_statement_by_user_id($item['user_id']);
        ?>
    <tr>
        <td>
            <?=($key+1)?>
        </td>
        <td>
            <?=($item) ?  user_helper::full_name($item['user_id']) : $id;?>
        </td>
        <td>
            <? if($udata['region_id']){ ?>
                <? $region = geo_peer::instance()->get_region($udata['region_id']) ?>
                <?=$region['short']?>
            <? } ?>
        </td>
        <td>
            <?=($item['remove_type']) ? membership_helper::get_party_off_types($item['remove_type']) : ' - ';?>
        </td>
        <td>
            <?
                switch($item['remove_type']) {
                    case 2:
                        echo membership_helper::get_party_off_auto_reason($item['removewhy']);
                        break;
                    case 3:
                        echo membership_helper::get_party_off_except_reason($item['removewhy']);
                        break;
                    default:
                        echo ' - ';
                        break;
                }
            ?>
        </td>
        <td>
            <?=($item) ?  date('d.m.Y',$item['removedate']) : $id;?>
        </td>
        <td>
            <?=($item['removenumber']) ?  $item['removenumber'] : ' - ';?>
        </td>
        <td>
            <?=($item) ?  user_helper::full_name($item['remove_from']) : ' - ';?>
        </td>
				<td>
					<? if($statement["id"]){ ?>
						<a href="/zayava/termination?act=print&id=<?=$statement["id"]?>">№ <?=$statement["id"]?></a>
					<? } else { ?>
						-
					<? } ?>
				</td>
    </tr>        
    
    <? } ?>
</table>
