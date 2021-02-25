<div class="left mt10" style="width: 35%;"><? include 'partials/left.php' ?></div>

<div class="left ml10 mt10" style="width: 62%;">
	<h1 class="column_head">Учасники з додатковими правами</h1>

	<div class="box_content acenter p10 mb10 fs11">
            <a href="/admin/credentialed?credential=redcollegiant"<?=request::get('credential')=='redcollegiant' ? 'style="color:black;"' : ''?>>Член редколлегії</a> |
            <a href="/admin/credentialed?credential=editor"<?=request::get('credential')=='editor' ? 'style="color:black;"' : ''?>>Публікатор</a> |
            <a href="/admin/credentialed?credential=admin"<?=request::get('credential')=='admin' ? 'style="color:black;"' : ''?>>Адмін</a> |
            <a href="/admin/credentialed?credential=moderator"<?=request::get('credential')=='moderator' ? 'style="color:black;"' : ''?>>Модератор</a> |
            <a href="/admin/credentialed?credential=selfmoderator"<?=request::get('credential')=='selfmoderator' ? 'style="color:black;"' : ''?>>Самомодератор</a> | 
            <a href="/admin/credentialed?credential=programmer"<?=request::get('credential')=='programmer' ? 'style="color:black;"' : ''?>>Програміст</a>
	</div>
        
	<? foreach ($users as $id) {
                include dirname(__FILE__) . '/../../people/views/partials/person.php';
	 } ?>
</div>
