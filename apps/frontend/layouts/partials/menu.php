<a class="fw-bold text-white text-uppercase" href="/"><?= t('Головна'); ?></a>&nbsp;&nbsp;
<?php if (session::is_authenticated()) { ?>
    <a class="fw-bold text-white text-uppercase"
       style="text-transform:uppercase;" href="/people"><?= t('Команда') ?></a>&nbsp;&nbsp;
<?php } ?>
<?php if (session::is_authenticated()) { ?>
    <a class="fw-bold text-white text-uppercase"
       style="text-transform:uppercase;" href="/ppo"><?= t('Организации') ?></a>&nbsp;&nbsp;
<?php } ?>
<?php
//if(session::has_credential('admin')){ ?>
<a class="fw-bold text-white text-uppercase" style="text-transform:uppercase;" href="/blogs/programs"><?= t('Успішна Україна'); ?></a>&nbsp;&nbsp;
<?php
//} ?>
<?php
if (session::has_credential('admin')) { ?>
    <?php
} ?>
<a class="fw-bold text-white text-uppercase"
   style="text-transform:uppercase;" href="/blogs"><?= t('Публикации'); ?></a>&nbsp;&nbsp;
<?php
if (session::is_authenticated()) { ?>
    <!-- <a class="fw-bold text-white text-uppercase" style="text-transform:uppercase;" href="/events"><?= t(
            'События'
    ); ?></a>&nbsp;&nbsp; -->
    <?php
} ?>

<?php
if (session::is_authenticated()) { ?>
    <a class="fw-bold text-white text-uppercase"
       style="text-transform:uppercase;" href="/library"><?= t('БИБЛИОТЕКА'); ?></a>&nbsp;&nbsp;
    <?php
    if (session::has_credential('admin')) { ?>
        <a class="fw-bold text-white text-uppercase"
           style="text-transform:uppercase;" href="/shop"><?= t('Online-крамниця'); ?></a>&nbsp;&nbsp;
        <?php
    } ?>
    <!--        <a class="fw-bold text-white text-uppercase" style="text-transform:uppercase" href="/deskhelp"><?= t(
            'ПОМОЩЬ'
    ); ?></a>&nbsp;&nbsp;-->
    <?php
} ?>
<?php
/*
 *        
<div class="subm <?=(context::get_controller()->get_module()=='search')?'choosenone':''?>">
<div class="mlm"></div>
<div class="mcm aleft bold" 
     onmouseover="document.getElementById('search_ul').style.display='block';" 
     onmouseout="document.getElementById('search_ul').style.display='none';" 
     style="margin-top: 1px; ">
 <a class="fw-bold text-white text-uppercase" href="/search">
            <?=t('ПОИСК');?>
 </a>&nbsp;&nbsp;
<div style="height: 10px;"></div>
<ul class="hidden pt5 menu_ul" id="search_ul">
<li><a href="/search">&nbsp;&nbsp;<?=t('Люди')?></a></li>
<li><a  href="/groups">&nbsp;&nbsp;<?=t('Группы')?></a></li>
</ul> 
</div>    
<div class="mrm"></div>
</div>
 */ ?>
<!--a class="bold <?= (context::get_controller()->get_module() == 'help' and strpos(
                $_SERVER['REQUEST_URI'],
                'media'
        )) ? '' : '' ?>" href="/help?media"><?= t('СМИ'); ?></a>&nbsp;&nbsp;-->
<?php
/*<a style="text-transform:uppercase;" class="fw-bold text-white text-uppercase" href="/signatures"><?=t('Подписи');?></a>&nbsp;&nbsp;*/ ?>

<?php
// load::model('feed/feed'); ?>

<?php
if (session::get("language") == 'ru') { ?>
    <style type="text/css">
        .head_pane .menu a {
            margin-right: 3px;
        }
    </style>
    <?php
} ?>
