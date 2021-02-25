<div onclick="Application.ShowHide('levels')" style="cursor: pointer;" class="column_head">
	<div class="left"><?= t('Уровень') ?></div>
	<div class="right mt5 <?= !$cur_category ? 'hide' : '' ?> icoupicon" style="cursor: pointer;" id="levels_on"></div>
	<div class="right mt5 <?= $cur_category ? 'hide' : '' ?> icodownt" style="cursor: pointer;" id="levels_off"></div>
</div>
<div id="levels" class="p10 box_content <?= !$cur_category ? 'hide' : '' ?>">
	<ul class="mb5">
		<?
		foreach (reform_peer::get_levels() as $level => $title) {
			if ($level != 0) { ?>
				<li><a href="/projects/index?category=<?= $level ?><?= user_helper::createlurl('category') ?>"
				       style="<?= $level == $cur_category ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
				</li>
			<? } ?>
		<? } ?>
	</ul>
</div>

<div onclick="Application.ShowHide('types')" style="cursor: pointer;" class="column_head mt10">
	<div class="left"><?= t('Тип') ?></div>
	<div class="right mt5 <?= !$cur_ptype ? 'hide' : '' ?> icoupicon" style="cursor: pointer;" id="types_on"></div>
	<div class="right mt5 <?= $cur_ptype ? 'hide' : '' ?> icodownt" style="cursor: pointer;" id="types_off"></div>
</div>
<div id="types" class="p10 box_content <?= !$cur_ptype ? 'hide' : '' ?>">
	<ul class="mb5">
		<?
		foreach (reform_peer::get_ptypes() as $ptype => $title) { ?>
			<li><a href="/projects/index?ptype=<?= $ptype ?><?= user_helper::createlurl('ptype') ?>"
			       style="<?= $ptype == $cur_ptype ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
			</li>
		<? } ?>
	</ul>
</div>

<? if (session::has_credential('admin')) { ?>
	<div onclick="Application.ShowHide('statuses')" style="cursor: pointer;" class="column_head mt10">
		<div class="left">*<?= t('Статус') ?></div>
		<div class="right mt5 <?= !$cur_status ? 'hide' : '' ?> icoupicon" style="cursor: pointer;"
		     id="statuses_on"></div>
		<div class="right mt5 <?= $cur_status ? 'hide' : '' ?> icodownt" style="cursor: pointer;"
		     id="statuses_off"></div>
	</div>
	<div id="statuses" class="p10 box_content <?= !$cur_status ? 'hide' : '' ?>">
		<ul class="mb5">
			<?
			foreach (reform_peer::get_statuses() as $status => $title) {
				if ($level != 0) { ?>
					<li><a href="/projects/index?status=<?= $status ?><?= user_helper::createlurl('status') ?>"
					       style="<?= $status == $cur_status ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?>; margin: 1px;"><?= $title ?></a>
					</li>
				<? } ?>
			<? } ?>
		</ul>
	</div>
<? } ?>

<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('regions')">
	<div class="left"><?= t('Регион') ?></div>
	<div class="right mt5 <?= !$cur_region ? 'hide' : '' ?> icoupicon" id="regions_on" style="cursor: pointer;"></div>
	<div class="right mt5 <?= $cur_region ? 'hide' : '' ?> icodownt" id="regions_off" style="cursor: pointer;"></div>
</div>
<div class="p10 box_content <?= !$cur_region ? 'hide' : '' ?>" id="regions">
	<?
	?>
	<div class="fs11 left ml10"><a href="javascript:;" id="az" class="areg hide">Назва &#9660;</a><a href="javascript:;"
	                                                                                                 id="za"
	                                                                                                 class="areg">Назва
			&#9650;</a></div>
	<div class="fs11 right mr15"><a href="javascript:;" id="rate" class="areg hide">Рейтинг &#9650;</a><a
			href="javascript:;" id="unrate" class="areg">Рейтинг &#9660;</a></div>
	<br>

	<ul class="mb5 dreg" id="ul_az">
		<? foreach ($az_regions as $region) { ?>
			<li><a href="/projects?region=<?= $region['id'] ?><?= user_helper::createlurl('region') ?>"
			       style="<?= $cur_region == $region['id'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?> margin: 1px;"><?= $region['title'] ?></a>

				<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
			</li>
		<? } ?>
	</ul>

	<ul class="mb5 dreg hide" id="ul_za">
		<? rsort($az_regions);
		foreach ($az_regions as $region) { ?>
			<li><a href="/projects?region=<?= $region['id'] ?><?= user_helper::createlurl('region') ?>"
			       style="margin: 1px;"><?= $region['title'] ?></a>

				<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
			</li>
		<? } ?>
	</ul>

	<ul class="mb5 dreg hide" id="ul_unrate">
		<? foreach ($rate_regions as $region_count => $region) { ?>
			<li><a href="/projects?region=<?= $region['id'] ?><?= user_helper::createlurl('region') ?>"
			       style="margin: 1px;"><?= $region['title'] ?></a>

				<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
			</li>
		<? } ?>
	</ul>

	<ul class="mb5 dreg hide" id="ul_rate">
		<?
		foreach (array_reverse($rate_regions) as $region_count => $region) { ?>
			<li><a href="/projects?region=<?= $region['id'] ?><?= user_helper::createlurl('region') ?>"
			       style="margin: 1px;"><?= $region['title'] ?></a>

				<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
			</li>
		<? } ?>
	</ul>
</div>

<? if (request::get_int('region')) { ?>
	<div class="column_head mt10" style="cursor: pointer;" onclick="Application.ShowHide('cities')">
		<div class="left"><?= t('Район') ?></div>
		<div class="right mt5 <?= !$cur_citi ? 'hide' : '' ?> icoupicon" id="regions_on" style="cursor: pointer;"></div>
		<div class="right mt5 <?= $cur_citi ? 'hide' : '' ?> icodownt" id="regions_off" style="cursor: pointer;"></div>
	</div>
	<div class="p10 box_content <?= !$cur_citi ? 'hide' : '' ?>" id="cities">
		<?
		?>
		<div class="fs11 left ml10"><a href="javascript:;" id="az" class="areg hide">Назва &#9660;</a><a
				href="javascript:;" id="za" class="areg">Назва &#9650;</a></div>
		<div class="fs11 right mr15"><a href="javascript:;" id="rate" class="areg hide">Рейтинг &#9650;</a><a
				href="javascript:;" id="unrate" class="areg">Рейтинг &#9660;</a></div>
		<br>

		<ul class="mb5 dreg" id="ul_az">
			<? foreach ($az_cities as $region) { ?>
				<li><a href="/projects?city=<?= $region['id'] ?><?= user_helper::createlurl('city') ?>"
				       style="<?= $cur_citi == $region['id'] ? 'color: #772f23; font-weight: bold; text-decoration: none;' : '' ?> margin: 1px;"><?= $region['title'] ?></a>

					<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
				</li>
			<? } ?>
		</ul>

		<ul class="mb5 dreg hide" id="ul_za">
			<? rsort($az_cities);
			foreach ($az_cities as $region) { ?>
				<li><a href="/projects?city=<?= $region['id'] ?><?= user_helper::createlurl('city') ?>"
				       style="margin: 1px;"><?= $region['title'] ?></a>

					<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
				</li>
			<? } ?>
		</ul>

		<ul class="mb5 dreg hide" id="ul_unrate">
			<? foreach ($rate_cities as $region_count => $region) { ?>
				<li><a href="/projects?city=<?= $region['id'] ?><?= user_helper::createlurl('city') ?>"
				       style="margin: 1px;"><?= $region['title'] ?></a>

					<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
				</li>
			<? } ?>
		</ul>

		<ul class="mb5 dreg hide" id="ul_rate">
			<?
			foreach (array_reverse($rate_cities) as $region_count => $region) { ?>
				<li><a href="/projects?city=<?= $region['id'] ?><?= user_helper::createlurl('city') ?>"
				       style="margin: 1px;"><?= $region['title'] ?></a>

					<div class="cbrown right fs11 bold"><?= $region['count'] ?></div>
				</li>
			<? } ?>
		</ul>
	</div>
<? } ?>

<script type="text/javascript">
	$(document).ready(function () {
		$('.areg').click(function () {
			$('.areg').removeClass('bold');
			var id = this.id;
			$(this).hide();
			if (id == 'az') {
				$('#za').show();
				$('#za').addClass('bold');
			} else if (id == 'za') {
				$('#az').show();
				$('#az').addClass('bold');
			} else if (id == 'unrate') {
				$('#rate').show();
				$('#rate').addClass('bold');
			} else if (id == 'rate') {
				$('#unrate').show();
				$('#unrate').addClass('bold');
			}

			$('.dreg').hide();
			$('#ul_' + id).show();
		});
	});
</script>