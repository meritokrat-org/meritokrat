<? foreach ($list as $user) { ?>
	<div style="width: 100px; height: 220px; margin: 8px; padding: 5px; background: #e1e1e1; float: left;" data-user-id="<?=$user["id"]?>">
		<img style="width: 100px; height: 130px;" src="https://image.meritokrat.org/p/user/<?=$user["id"].$user["salt"]?>.jpg">
		<div style="height: 50px; font-size: 12px">
			<b><?=$user["fname"]." ".$user["lname"]?></b>
		</div>
		<b>ID: </b><?=$user["id"]?>
		<a class="delete_photo" data-user-id="<?=$user["id"]?>" style="cursor:pointer;">Удалить фото</a>
		<div style="clear: both"></div>
	</div>
<? } ?>

<div class="bottom_line_d mb10"></div>
<div class="right pager"><?= pager_helper::get_full($pager) ?></div>

<script>
	$(document).ready(function(){

		$(".delete_photo").click(function(){
			var user_id = $(this).attr("data-user-id");

			$.post("/admin/users_photo", {
				user_id: user_id
			}, function(res){
				if(res.success){
					$("div[data-user-id="+user_id+"]").css({
						opacity: ".3"
					});
					$("a[data-user-id="+user_id+"]").remove();
				}
				else
					alert("Ошибка удаления");
			}, "json");
		});

	});
</script>