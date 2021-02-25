<style>
	table {
		border: 1px solid #ccc;
		border-collapse: collapse
	}
	
	table thead tr th {
		background: #913D3E;
		color: #FC6;
	}
	
	table thead tr th, table tbody tr td {
		font-size: 11px;
		border: 1px solid #888;
		vertical-align: middle;
	}
	table tbody tr#total td {
		font-weight: bold;
		background: #ccc;
	}
</style>

<div>
	<div class="mb10" style="background: #600; padding: 10px; color: white">
		<? if(request::get_int('region_id') || request::get_int('city_id')){ ?>
			<span><a href="/election" style="color: white; text-decoration: underline"><?=t('Выборы 2012')?></a></span> /
			<? if(request::get_int('region_id')) { ?>
				<span><?= ($region = geo_peer::instance()->get_region(request::get_int('region_id'))) ? $region['name_ua'] : '' ?></span>
			<? } elseif(request::get_int('city_id')){ ?>
				<span><?= ($city = geo_peer::instance()->get_city(request::get_int('city_id'))) ? $city['name_ua'] : '' ?></span>
			<? } ?>
		<? } else { ?>
			<span><?=t('Выборы 2012')?></span>
		<? } ?>
		
	</div>
	<div style="width: 1000px; overflow: auto;">
		<table border="1">
			<thead>
				<tr>
					<th rowspan="2">
						<? if($frame != 'locations'){ ?>
							<?=t('ФИО')?>
						<? } else { ?>
							<?=t('Регион')?>
						<? } ?>
					</th>
					<th colspan="3"><?=t('Проголосуют')?></th>
					<th colspan="2"><?=t('Финансы')?></th>
					<th colspan="3"><?=t('Агитация')?></th>
					<th colspan="2"><?=t('Волонтеры')?></th>
					<th colspan="2"><?=t('Кандидаты')?></th>
					<th colspan="3"><?=t('На выборах')?></th>
				</tr>
				<tr>
					<th><?=t('Лично')?></th>
					<th><?=t('Сагитировано')?></th>
					<th><?=t('Вместе')?></th>
					<th><?=t('Готовы')?></th>
					<th><?=t('Сумма')?></th>
					<th><?=t('Свои')?></th>
					<th><?=t('Интернет')?></th>
					<th><?=t('Уличная')?></th>
					<th><?=t('Центр')?></th>
					<th><?=t('Регионы')?></th>
					<th><?=t('Округ')?></th>
					<th><?=t('Список')?></th>
					<th><?=t('Наблюдатель')?></th>
					<th><?=t('Комиссия')?></th>
					<th><?=t('Юрист')?></th>
				</tr>
			</thead>
			<tbody>
				<? include 'index/'.$frame.'.php' ?>
			</tbody>
		</table>
	</div>
</div>