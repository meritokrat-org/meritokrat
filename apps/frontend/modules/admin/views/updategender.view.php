<form action="" method="post">
<?if($users)foreach($users as $u){
$key=mb_strtolower(mb_substr($u['first_name'], mb_strlen($u['first_name'])-1));
$key2=mb_strtolower(mb_substr($u['first_name'], mb_strlen($u['first_name'])-2)); 
?>

<b><?=(in_array($key,array("а","я")) || in_array($key2,array("ов"))?'Ж':'М')?></b> <?=user_helper::full_name($u['user_id'])?>
<input type="radio" name="users[<?=$u['user_id']?>]" <?=(in_array($key,array("а","я")) || in_array($key2,array("ов"))?'':'checked')?> value="m" /><b>М</b>
<input type="radio" name="users[<?=$u['user_id']?>]" <?=(in_array($key,array("а","я")) || in_array($key2,array("ов"))?'checked':'')?> value="f" /><b>Ж</b><br/>
<? }else echo "Успешно"; ?>
<?if($users){?><input type="submit" name="send"/><?}?>
</form>
