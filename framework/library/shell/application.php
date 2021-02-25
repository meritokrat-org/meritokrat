<?

class shell_application extends application
{
	public function init_error_handling()
	{
		$handler_class = 'shell_error';
		
		load::system('error/' . $handler_class);
		call_user_func("{$handler_class}::handle");

		mem_cache::i()->disable_inner_cache();
	}
}