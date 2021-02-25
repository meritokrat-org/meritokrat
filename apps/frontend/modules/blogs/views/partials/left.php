
<style>
.fl {
    float: left;
    clear: none;
}
.cb {
    float: none;
    clear: both;
}
#user_list2 {
    position: absolute;
    width: 180px;
}
.one_user_box {
    z-index: 100;
    background: #fff;
    position: relative;
    padding: 1px 10px;
    border: 1px solid #ccc;
    width: 180px;
}
.one_user_ava_box {}
.one_user_ava_box img {
    width: 32px;
    height: 40px;
}
.one_user_info_block {
    margin-left: 10px;
     width: 135px;
}
.one_user_name {
    line-height: 20px;

}
.one_user_city {
    line-height: 20px;
}
.user_selected {
    background-color: #dddddd;
}
</style>
<h1 class="column_head"><?=t('Поиск')?></h1>
<div class="p10 box_content acenter">
    <div id="fastsrch" class="blogsearch mb5 <?=(request::get('author') OR request::get('sfera') OR request::get('name') OR request::get('text') OR request::get('start') OR request::get('end'))?'hide':''?>">
    <center><form id="blog_search" action="/blogs" method="post">
        <input type="text" name="fast" class="text" style="width: 150px; border: 1px solid #999999" value="<?=  request::get('fast');?>">
        <input type="hidden" name="submit" class="text left" value="1"><br/>
        <input type="submit" class="mt10 button" value="<?=t('Поиск')?>" >
    </form></center>
    </div>
    <div id="advancedsrch" style="text-align:left" class="blogsearch <?=(request::get('author') OR request::get('sfera') OR request::get('name') OR request::get('text') OR request::get('start') OR request::get('end'))?'':'hide'?>">
    <form action="/blogs" method="GET" onsubmit="$('#user_id').val($('#search_users').attr('uid')); return true;">
        <input type="hidden" name="user_id" id="user_id" value="<?=request::get_int('user_id')?>"/>
        <span class="cgray fs11"><?=t('Автор')?>:</span>
        <input name="author" id="search_users" type="text" class="text mb5" style="width:235px" value="<?=(request::get_int('user_id'))?user_helper::full_name(request::get_int('user_id'),false,array(),false):''?>"/><br/>
        <div id="user_list2" class="cb" style="display: none;"></div>
        <span class="cgray fs11"><?=t('Часть слова или фраза из названия')?>:</span><br/>
        <input name="name" type="text" class="text mb5" style="width:235px" value="<?=htmlspecialchars(stripslashes(request::get_string('name')))?>"/>
        <span class="cgray fs11"><?=t('Часть слова или фраза из основного текста')?>:</span><br/>
        <input name="text" type="text" class="text mb5" style="width:235px" value="<?=htmlspecialchars(stripslashes(request::get_string('text')))?>"/>

        <span class="cgray fs11"><?=t('Сфера')?>:</span>
        <? $sfr = blogs_posts_peer::get_sferas() ?>
        <? $sfr[0] = '&mdash;';ksort($sfr) ?>
        <?=tag_helper::select('sfera', $sfr, array('class'=>'mb15','style'=>'width:240px','value'=>(request::get_int('sfera') ? request::get_int('sfera') : 0)))?>
        
        <table class="mb15">
        <tr>
            <td class="cgray fs11" style="vertical-align:middle;padding:0" width="50"><?=t('Дата с')?>: </td>
            <td style="padding:0 0 5px 0">
            <? if(request::get_int('start_day') && request::get_int('start_month') && request::get_int('start_year')){
                $start = mktime(0, 0, 0, request::get_int('start_month'), request::get_int('start_day'), request::get_int('start_year'));
            } ?>
            <?=user_helper::datefields('start',intval($start),false,array(),true)?>
            </td>
        </tr><tr>
            <td class="cgray fs11" style="vertical-align:middle;padding:0"><?=t('Дата по')?>: </td>
            <td style="padding:0">
                <? if(request::get_int('end_day') && request::get_int('end_month') && request::get_int('end_year')){
                $end = mktime(0, 0, 0, request::get_int('end_month'), request::get_int('end_day'), request::get_int('end_year'));
                } ?>
                <?=user_helper::datefields('end',intval($end),false,array(),true)?>
            </td>
        </tr>
        </table>

        
        <input name="submit" type="submit" class="button" value="<?=t('Поиск')?>" style="margin-left:95px"/>
    </form>
    </div>
    <a href="javascript:;" class="fs12 advance <?=(request::get('author') OR request::get('sfera') OR request::get('name') OR request::get('text') OR request::get('start') OR request::get('end'))?'hide':''?>"><?=t('Расширенный поиск')?></a>
</div>
<br />

<!--<h1 class="column_head"><?=t('Метки')?></h1>
<div class="p10 box_content acenter">
	<? if ( !$top_tags ) echo '<div class="fs11 quiet">Меток нет</div>'; ?>
	<? foreach ( $top_tags as $tag_data ) { ?>
		<? $name = blogs_tags_peer::instance()->get_name($tag_data['id']); ?>
		<a href="/blogs/index?tag=<?=stripslashes(htmlspecialchars($name))?>" style="<?= $name==$tag ? 'color: #772f23; text-decoration: none;' : ''?>; font-size: <?=9+$tag_data['weight']?>px; margin: 1px;"><?=stripslashes($name)?></a>
	<? } ?>
</div>-->

<?php if(session::is_authenticated()){ ?>
<h1 class="column_head"><?=t('Учасники')?></h1>
<div>
    <? foreach ($random_users as $id) { ?>
        <?include 'person.php'; ?>
    <? } ?>
</div>
<?php } ?>

<? if ($sub_menu != '/blogs/new' && 1==2) { ?>
<br />
	<h1 class="column_head"><?=t('Свежие записи')?></h1>
	<div class="clear"></div>
<? foreach ( $newest as $id ) { ?>
	<? $post_data = blogs_posts_peer::instance()->get_item($id) ?>
		<div style="background:#F7F7F7;" class="p5 mb10">
			<div class="left fs11" style="margin-top: 3px;"><?=date('H:i', $post_data['created_ts'])?></div>
			<div class="left ml10" style="width: 180px;">
				<a class="<?=$post_data['rate'] > 5 ? 'bold' : ''?> fs12" href="/blogpost<?=$id?>"><?=stripslashes(htmlspecialchars($post_data['title']))?></a>
				<div class="fs11"><?=user_helper::full_name($post_data['user_id'], true, array('class' => 'quiet'))?></div>
			</div>
			<div class="clear"></div>
		</div>
<? } ?>
<div class="right pager"><a href="/blogs/new"><?=t('Все свежие записи')?></a></div>
<? } ?>

<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="/static/javascript/jquery/jquery.ui.datepicker-uk.js"></script>
<script type="text/javascript">
    $(document).ready(function($){
			
        var settings = {
        changeMonth: true,
        changeYear: true,
         autoSize: true,
        showOptions: {direction: 'left' },
        dateFormat: 'dd-mm-yy',
        yearRange: '2010:2025',
        firstDay: true
        };

        $('#search_users').change(function(){
            if($(this).val()==''){
              $('#user_id').val('');
						}
        });
        $('#start').datepicker(settings);
        $('#end').datepicker(settings);
        $('.advance').unbind('click').click(function(){
            $('#fastsrch').hide();
            $('#advancedsrch').show();
            $(this).hide();
        });
    });
</script>