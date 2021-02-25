<?
request::get_int('thread_id') ? $message = messages_peer::instance()->get_item($id) : $message =db::get_row("SELECT * FROM messages WHERE thread_id=:thread_id ORDER by id DESC  LIMIT 1", array('thread_id'=>$new_thread_id));
?>
<? include 'partials/message.php' ?>