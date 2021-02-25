<div id="showinfoboxtitle"><?=(session::get('language')!='ru')?stripslashes($info['name_ua']):stripslashes($info['name_ru'])?></div>
<div id="showinfoboxdata"><?=(session::get('language')!='ru')?stripslashes($info['text_ua']):stripslashes($info['text_ru'])?></div>
<div id="showinfoboxbuttons"><?=(session::get('language')!='ru')?stripslashes($info['admin_text_ua']):stripslashes($info['admin_text_ru'])?></div>
