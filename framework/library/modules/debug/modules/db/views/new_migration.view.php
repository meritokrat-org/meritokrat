<h2><a href="/debug">Debug</a> &rarr; <a href="/debug/db/index">База данных</a> &rarr; Создать миграцию</h2>
<div class="line_light"></div>

<br />

<div class="success hidden">Миграция создана</div>

<textarea class="field" id="sql" cols="60" rows="10"></textarea><br />
<input type="button" value=" OK " onclick="if ( $('#sql').val() ) { $.post('/debug/db/new_migration',{sql:$('#sql').val()},function(){ $('.success').show(); }) }" />

<br /><br />