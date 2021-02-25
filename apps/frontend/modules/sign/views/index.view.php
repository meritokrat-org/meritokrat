<div class="acenter mt10">

    <?/* if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0') or strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0') ) { ?>

                <form id="signin_form" action="/sign/autologin">
                    <center>
                    <table style="width:40%;text-align: left;">
                    <tr><td class="aright">
                            <span style="color:white"> Email &nbsp; &nbsp; </span></td>
                        <td>
                            <input type="text" class="text" name="email" rel="required:<?=t('Введите, пожалуйста')?>, email;email:<?=t('Вы ввели неправильный email')?>;" />
                    </td>
                    </tr>
                    <tr><td class="aright">
                            <span style="color:white">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=t('Пароль')?></span>
                    </td>
                    <td>
                            <input type="password" class="text" name="password" rel="required:<?=t('Введите, пожалуйста, пароль')?>" /> &nbsp;&nbsp;
                            <input type="submit" name="submitos" class="button" style="background-color:gray;" value=" <?=t('Войти')?> ">
                    </td></tr>

                    <tr><td></td>
                            <td><span style="color:white; font-size: 11px"><input type="checkbox" checked> <?=t('Запомнить')?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
                    </tr>
                    
                    <tr><td></td>
                        <td><div class="mb5"><a href="/sign/recover" style="color:white; font-size: 12px"><?=t('Забыли пароль')?>?</a></div></td>
                    </tr>
                    <tr>
                            <td colspan="2" style="text-align:center">
                                <span style="color:white; font-size: 12px">
                                    Про мерітократію та як стати учасником цієї мережі читайте на
                                    <a href="https://www.meritokratia.info" target="_blank" style="color:white;font-weight:bold;">www.meritokratia.info</a>
                                </span>
                            </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="acenter"><?=tag_helper::wait_panel() ?></td>
                    </tr>
                    </table>
                    </center>
                </form>
            <? } else {  */?>

                <form id="signin_form" action="/sign/in" method="post">
                    <center>
                    <table style="width:280px;text-align: left;">
                    <tr>
                        <td class="aright fs12 cwhite">
                            Email &nbsp;&nbsp;
                        </td>
                        <td>
                            <input type="text" style="width:200px;border-color:#4e0006" class="text" name="email" rel="required:<?=t('Введите, пожалуйста')?>, email;email:<?=t('Вы ввели неправильный email')?>;" />
                        </td>
                    </tr>
                    <tr>
                        <td class="aright fs12 cwhite">
                            <?=t('Пароль')?>
                        </td>
                        <td>
                                <input type="password" style="width:200px;border-color:#4e0006" class="text" name="password" rel="required:<?=t('Введите, пожалуйста, пароль')?>" />
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" style="padding:10px 0 0 0">
                            <div class="fs11 left ml5" style="color:#C08D8D">
                                <div class="echeckbox chon">
                                    <input type="checkbox" class="entercheckbox" checked="checked">
                                </div>
                                <?=t('Запомнить')?>
                            </div>
                            <div class="fs11 right aright mr10">
                            <input type="submit" name="submit" class="button_enter<?=(session::get('language')=='ru')?'_ru':''?> mb5"  value=" <?=t('Войти')?> ">
                            <br/>
                            <a href="/sign/recover" class="fs11" style="color:#C08D8D"><?=t('Забыли пароль')?>?</a>
                            </div>
                        </td>
                    </tr>
                    <!--tr>
                        <td colspan="2" style="text-align:center;padding-top:20px">
                            <a href="/sign/up" class="cwhite bold fs14"><?=t('Зарегистрироваться')?></a>
                        </td>
                    </tr-->

                    <tr>
                        <td colspan="2" class="acenter"><?=tag_helper::wait_panel() ?></td>
                    </tr>
                    </table>
                    </center>
                </form>
    <?// } ?>
</div>

<div class="clear"></div>
<!--
<?=$_SERVER['HTTP_USER_AGENT']?>

-->
