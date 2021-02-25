<? $user = user_auth_peer::instance()->get_item(session::get_user_id()) ?>
<? if($user['status'] == 20){ ?>
	<h1 style="cursor: pointer;" class="column_head mb10"><a style="display:block" href="/ppo"><?= t('Партийные организации') ?></a></h1>
<? } ?>

<div onclick="Application.ShowHide('categories')" style="cursor: pointer;" class="column_head">
	<div class="left"><?= t('Категория') ?></div>
	<div class="right mt5 hide icoupicon" style="cursor: pointer;" id="categories_on"></div>
	<div class="right mt5 icodownt" style="cursor: pointer;" id="categories_off"></div>
</div>
<div class="p10 box_content hide" id="categories">
	<ul class="mb5">
		<? foreach (groups_peer::get_categories() as $category => $title) { ?>
			<? if ($category != 4 || (session::has_credential('admin') || db::get_scalar('SELECT count(*) FROM groups_members WHERE user_id=:user_id AND group_id IN (SELECT id FROM groups WHERE category=4)', array('user_id' => session::get_user_id())))) { ?>
				<li><a href="/groups/index?category=<?= $category ?>"
				       style="<?= $category == $cur_category ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
				</li><? } ?>
		<? } ?>
	</ul>
</div>

<div onclick="Application.ShowHide('sferas')" style="cursor: pointer;" class="column_head mt10">
	<div class="left"><?= t('Сфера') ?></div>
	<div class="right mt5 hide icoupicon" style="cursor: pointer;" id="sferas_on"></div>
	<div class="right mt5 icodownt" style="cursor: pointer;" id="sferas_off"></div>
</div>
<div id="sferas" class="p10 box_content hide">
	<ul class="mb5">
		<?
		$sql = 'SELECT DISTINCT type FROM groups';
		$type_exists = db::get_cols($sql, array());
		foreach (groups_peer::get_types() as $type => $title) {
			if (in_array($type, $type_exists)) { ?>
				<li><a href="/groups/index?type=<?= $type ?>"
				       style="<?= $type == $cur_type ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
				</li>
			<? } ?>
		<? } ?>
	</ul>
</div>

<? if($user['status'] == 20){ ?>
<div onclick="Application.ShowHide('levels')" style="cursor: pointer;" class="column_head mt10">
	<div class="left"><?= t('Уровень') ?></div>
	<div class="right mt5 hide icoupicon" style="cursor: pointer;" id="levels_on"></div>
	<div class="right mt5 icodownt" style="cursor: pointer;" id="levels_off"></div>
</div>
<div id="levels" class="p10 box_content hide">
	<ul class="mb5">
		<?
		foreach (groups_peer::get_levels() as $level => $title) {
			if ($level != 0) { ?>
				<li><a href="/groups/index?level=<?= $level ?>"
				       style="<?= $level == $cur_type ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
				</li>
			<? } ?>
		<? } ?>
	</ul>
</div>
<? } ?>

<? if (session::has_credential('admin')) { ?>
	<div onclick="Application.ShowHide('filters')" style="cursor: pointer;" class="column_head mt10">
		<div class="left">*<?= t('Фільтри') ?></div>
		<div class="right mt5 hide icoupicon" style="cursor: pointer;" id="filters_on"></div>
		<div class="right mt5 icodownt" style="cursor: pointer;" id="filters_off"></div>
	</div>
	<div id="filters" class="p10 box_content <?= request::get('filter') ? '' : 'hide' ?>">
		<ul class="mb5">
			<li><a href="/groups/index?filter=approved"
			       style="<?= request::get('filter') == 'approved' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Схвалені</a>
			</li>
			<li><a href="/groups/index?filter=not_approved"
			       style="<?= request::get('filter') == 'not_approved' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Не
					схвалені</a></li>
			<li><a href="/groups/index?filter=open"
			       style="<?= request::get('filter') == 'open' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Відкриті</a>
			</li>
			<li><a href="/groups/index?filter=glosed"
			       style="<?= request::get('filter') == 'glosed' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Закриті</a>
			</li>
			<li><a href="/groups/index?filter=hidden"
			       style="<?= request::get('filter') == 'hidden' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Скриті</a>
			</li>
			<li><a href="/groups/index?filter=public"
			       style="<?= request::get('filter') == 'public' ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;">Публічні</a>
			</li>
		</ul>
	</div>
<? } ?>

<? /*

<h1 class="column_head"><?=t('Территория')?></h1>
<div class="p10 box_content">
	<ul class="mb5">
		<? foreach ( groups_peer::get_teritories() as $teritory => $title ) { ?>
		<li><a href="/groups/index?teritory=<?=$teritory?>" style="<?= $teritory==$cur_teritory ? 'color: #772f23; font-weight: bold; text-decoration: none;' : ''?>; margin: 1px;"><?=$title?></a></li>
	<? } ?>
	</ul>
</div>
 <?
 */
?>