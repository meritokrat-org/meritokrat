<div onclick="hideAdmMenu('ladm')" style="cursor: pointer; padding-top: 30px" class="right">
	<img id="mstrl" src="/static/images/common/btn_arrow_left_gray.jpg">
</div>
<h1 class="column_head" id="ladmh"><?= t('Меню') ?></h1>
<div class="fs11 p10 box_content fs12" id="ladm">
	<ul style="list-style: none">
		<li style="margin-top: 10px"><b><?=t("Пользователи")?></b></li>
		<li><a href="/admin/users"><?= t('Пользователи') ?></a></li>
		<li><a href="/admin/recommend_status?all=1">Рекомендації</a></li>
		<li><a href="/reestr"><?= t('Реестр членов') ?></a></li>
		<li><a href="/pporeestr"><?= t('Реестр ПО') ?></a></li>
		<li><a href="/reestr/payments"><?= t('Взносы') ?></a></li>
		<li><a href="/admin/duplicate"><?= t('Профили дубликаты') ?></a></li>
		<li><a href="/admin/credentialed">Учасники з дод.правами</a></li>

		<!--li><a href="/admin/translations"><?= t('Перевод') ?></a></li-->
		<!--li><a href="/admin/groups">Спiльноти</a></li-->

		<li></li>
		<li style="margin-top: 10px"><b><?=t("М-Магазин")?></b></li>
		<li><a href="/admin/shop_categories"><?=t("Категории товаров")?></a></li>
		<li><a href="/admin/shop_items"><?=t("Товары")?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Рассылка")?></b></li>
		<li><a href="/admin/maillist">E-mail розсилка</a></li>
		<li><a href="/admin/editlists">Списки розсилок</a></li>
		<li><a href="/admin/internallist">Внутрішня розсилка</a></li>
		<li><a href="/admin/mails"><?= t('Шаблоны сообщений') ?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Разное")?></b></li>
		<li><a href="/admin/info"><?= t('Подсказки') ?></a></li>
		<li><a href="/inquirer/">Примусові опитування</a></li>
		<li><a href="/admin/help"><?= t('Текстовые страницы') ?></a></li>
		<li><a href="/docs"><?= t('Большие документы') ?></a></li>
		<li><a href="/admin/attentions">Важлива інформація(головна)</a></li>
		<!--li><a href="/ideas/create">Додати Ідею (Партія)</a></li-->

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Агитация")?></b></li>
		<li><a href="/results/agitation"><?= t('Статисткика по агитматериалам') ?></a></li>
		<li><a href="/eventreport"><?= t('Отчеты про агитацию') ?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Модараторам")?></b></li>
		<li><a href="/admin/mfeed"><?= t('Лента модераторов') ?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Финансы")?></b></li>
		<li><a href="/admin/vnesky">Членські внески</a></li>
		<li><a href="/admin/mpu_payments"><?= t('Благотворительные взносы') ?></a></li>
		<li><a href="/admin/currency">Курс валют</a></li>
		<!--li><a href="/admin/complaint"><?= t('Лента жалоб') ?></a></li-->

		<li></li>
		<li style="margin-top: 10px"><b>SEO</b></li>
		<li><a href="/admin/seo_text"><?= t('SEO текст') ?><?= t('ы') ?></a></li>
		<li><a href="/admin/seo_tags"><?= t('SEO теги') ?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Рейтинги")?></b></li>
		<li><a href="/admin/rating"><?= t('Рейтинги') ?></a></li>
		<li><a href="/admin/rating_cost"><?= t('Настройки') ?></a></li>

		<li></li>
		<li style="margin-top: 10px"><b><?=t("Настройки")?></b></li>
		<li><a href="/admin/ban_ip"><?= t('Забаненные АйПи') ?></a></li>
		<li><a href="/admin/referals"><?= t('Джерела приєднання') ?></a></li>

		<li style="margin-top: 10px"><b><?=t("Дополнительно")?></b></li>
		<li><a href="/admin/election"><?= t('Выборы 2012') ?></a></li>
	</ul>
</div>
<script type="text/javascript">
	$('#mstrl').toggle(function () {
		$('#ladm').show();
		$('#ladmh').show();
		$('#lmcol').css('width', '35%');
		$('#rmcol').css('width', '62%');
		$('#mstrl').attr('src', '/static/images/common/btn_arrow_left_gray.jpg');
	}, function () {
		$('#ladm').hide();
		$('#ladmh').hide();
		$('#lmcol').css('width', '0%');
		$('#rmcol').css('width', '100%');
		$('#mstrl').attr('src', '/static/images/common/btn_arrow_right_gray.jpg');
	});
</script>