<div class="ml10" style="width: 935px; margin-left: 8%;">
	<h4 class="mt15 fs28 cbrown"
	    style="text-decoration: none; margin-left: 160px;margin-bottom: 0px;"><?= t('Регистрация') ?>:</h4>

	<div class="left" style="width: 50%;margin-left: 5px;">
		<? if (request::get('submit')) { ?>
			<div class="error mt10">
				<?= t('Форма заполнена некорректно') ?>
				<? $error ? '<br>' . $error : '' ?>
			</div>
		<? } ?>
		<div class="success hidden mr10 mt10">
			<h4><?= t('Поздравляем') ?>!</h4>
			<?= t('Благодарим за регистрацию! Вскоре мы проверим ваш профиль и на указанный Вами email отправим сообщение про подтверждение Вашей регистрации') ?>
		</div>
		<form id="signup_form" method="post">
			<table class="fs12 mr10 mt5">

				<? if (request::get_int('i')) { ?>
					<input type="hidden" name="inviter_id" value="<?= request::get_int('i') ?>"/>

					<tr>
						<td class="aright"><?= t('Вас пригласил') ?></td>
						<td>
							<?= user_helper::photo(request::get_int('i'), 't') ?> <br/>
							<?= user_helper::full_name(request::get_int('i')) ?>
						</td>
					</tr>

				<? } ?>
				<tr>
					<td class="aright" style="width:125px;"><b><?= t('Фамилия') ?></b></td>
					<td>
						<input style="width:155px" value="<?= htmlspecialchars($name[1]) ?>" type="text" class="text"
						       id="last_name" name="last_name" rel="<?= t('Введите, пожалуйста, Ваше полное имя') ?>"/>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Имя') ?></b></td>
					<td>
						<input style="width:155px" value="<?= htmlspecialchars($name[0]) ?>" type="text" class="text"
						       id="first_name" name="first_name"
						       rel="<?= t('Введите, пожалуйста, Ваше полное имя') ?>"/>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Страна') ?></b></td>
					<td>
						<input name="country_id" type="hidden" value="<?= $user_data['country_id'] ?>"/>
						<? load::model('geo') ?>
						<?
						$сountries = geo_peer::instance()->get_countries();
						$сountries[''] = t(' - выберите страну - ');
						unset($сountries[0]);
						ksort($сountries);
						?>
						<?= tag_helper::select('country', $сountries, array('use_values' => false, 'style' => "width:160px", 'value' => $user_data['country_id'], 'id' => 'country', 'name' => 'country', 'rel' => t('Выберите страну'))) ?>
					</td>
				</tr>
				<tr id="region_row">
					<td class="aright"><b><?= t('Регион') ?></b></td>
					<td>
						<input name="region_id" type="hidden" value="<?= $user_data['region_id'] ?>"/>
						<input name='region_txt' type='text' style="display: none" id='region_txt'>
						<? $regions[''] = '&mdash;'; ?>
						<?= tag_helper::select('region', $regions, array('use_values' => false, 'style' => "width:160px", 'value' => $region_id, 'id' => 'region', "disabled" => "disabled", 'name' => 'region', 'rel' => t('Выберите регион'))); ?>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Город/Район') ?></b></td>
					<td>
						<input name="city_id" type="hidden" value="<?= $user_data['city_id'] ?>"/>
						<input name='city_txt' type='text' style="display: none" id='city_txt'>

						<? $cities[''] = '&mdash;'; ?>
						<?= tag_helper::select('city', $cities, array('use_values' => false, 'value' => $user_data['city_id'], 'style' => "width:160px", "disabled" => "disabled", 'id' => 'city', 'name' => 'city', 'rel' => t('Выберите город/район'))); ?>
					</td>
				</tr>
				<tr>
					<td class="aright"><b>Email *</b></td>
					<td>
						<input style="width:155px" value="<?= htmlspecialchars($email) ?>" type="text" class="text"
						       id="email" name="email"
						       rel="required:<?= t('Введите, пожалуйста') ?>, email;email:<?= t('Вы ввели неправильный') ?> email;"/>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t("Телефон") ?> *</b></td>
					<td>
						<input style="width:155px" value="<?= htmlspecialchars($phone) ?>" type="text" class="text"
						       id="phone" name="phone"
						       rel="required:<?= t('Введите, пожалуйста') ?>, <?= t("телефон") ?>;"/>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Откуда Вы узнали про меритократию'); ?>?<b></td>
					<td>
						<input type="radio" name="referer" value="7" class="referer">
						<label for="referer_7"><?= t('От родственников') ?></label><br/>
						<input type="radio" name="referer" value="8" class="referer">
						<label for="referer_8"><?= t('От друзей/знакомых/колег') ?></label><br/>
						<input type="radio" name="referer" value="3" class="referer">
						<label for="referer_3"><?= t('С рекламы в соц. сетях') ?></label><br/>

						<div id="social_list" class="hide ml5">
							<div class="cb ml15">
								<input type="radio" name="rsocial" class="rsocial" value="facebook">
								<label for="rsocial_facebook"><?= t('Facebook') ?></label>
							</div>
							<div class="cb ml15">
								<input type="radio" name="rsocial" class="rsocial" value="vkontakte">
								<label for="rsocial_vkontakte"><?= t('ВКонтакте') ?></label></div>
							<div class="cb ml15">
								<input type="radio" name="rsocial" class="rsocial" value="other">
								<label for="rsocial_other"><?= t('Другой') ?></label></div>
							<div id="social_another" class="hide ml15">
								<input type="text" name="rsocial" id="rsocial" class="ml5">
							</div>
						</div>
						<input type="radio" name="referer" value="5" class="referer">
						<label for="referer_5"><?= t('От Игоря Шевченко') ?></label><br/>
						<input type="radio" name="referer" value="6" class="referer">
						<label for="referer_6"><?= t('Другое') ?></label><br/>

						<div class="text fl bold hide ml5" id="another">
							<input type="text" name="ranother" id="other">
						</div>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Волонтер экосотни') ?></b></td>
					<td><input <?= isset($_GET['eco100']) ? 'checked' : '' ?> id="eco100" type="checkbox" name="eco100"
					                                                          value="1"/>
						<label for="eco100"><?= t('Да') ?></label>
					</td>
				</tr>
				<tr>
					<td class="aright"><b><?= t('Язык') ?></b></td>
					<td>
						<input <?= $_SESSION['language'] != 'ru' ? 'checked' : '' ?> id="lang_ua" type="radio"
						                                                             name="language" value="ua"/>
						<label for="language_ua"><?= t('Украинский') ?></label>

						<input <?= $_SESSION['language'] == 'ru' ? 'checked' : '' ?> id="lang_ru" type="radio"
						                                                             name="language" value="ru"/>
						<label for="language_ru"><?= t('Русский') ?></label>
						<? /*
                                          <input <?=$_SESSION['language']=='en' ? 'checked' : ''?> id="lang_en" type="radio" name="language" value="en"/>
                                          <label for="language_en">English</label>
                                         * 
                                         */ ?>

					</td>
				</tr>
				<tr>
					<td colspan="2" class="fs12 mt15">
						<? //=t('Если по определенным причинам Вам не удается зарегистрироваться и присоединиться к меритократической команде, отправьте нам сообщение по электронной почте <a href="mailto: info@meritokraty.org">info@meritokraty.org</a> или звоните по телефону <b>(044) 492 92 92</b>'); ?>
					</td>
				</tr>
				<tr>
					<td></td>
					<td>
						<div class="mt10 mb10 fs11 quiet">
							* <?= t('Ваши телефон и e-mail будут храниться конфиденциально') ?></div>

						<input type="submit" name="submit" class="button bold"
						       value="&nbsp; &nbsp;<?= t('Зарегистрироваться') ?>&nbsp; &nbsp;" style="width:140px"/>
						<?= tag_helper::wait_panel() ?>

						<br/><br/>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div class="left" style="width: 40%;">
		<p>
			<?= t('Социальная сеть "Меритократ" объединяет людей, которые поддерживают <b><a href="http://shevchenko.ua/ua/useful/meritocracy/">идеи меритократии</a></b> и стремятся создать успешное общество в Украине и в мире') ?></a>
			.
		</p>
		<!--        <p>
<?= t('') ?>
                                    </p>-->
		<!--ul id="reglist fs14" class="ml10 cbrown">
				</ul-->
	</div>
	<div class="clear"></div>

</div>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		//радіо звідки дізналися
		$('.referer').click(function () {
			if ($(this).val() == 3) {
				$('#social_list').show();
				$('#another').hide();
			}
			else if ($(this).val() == 6) {
				$('#social_list').hide();
				$('#another').show();
			}
			else {
				$('#social_list').hide();
				$('#another').hide();
			}
		});
		//соцмережи
		$('.rsocial').click(function () {
			if ($(this).val() == 'other') {
				$('#social_another').show();
			}
			else {
				$('#social_another').hide();
			}
		});
	});
</script>
