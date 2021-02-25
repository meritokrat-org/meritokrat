<?

class redis_test_task extends shell_task
{
	public function execute()
	{
		load::system('db/key/db_key');

		db_key::i()->set('test', 'test');
		$this->out(db_key::i()->get('test'));
		var_dump(db_key::i()->exists('test'));
		$this->out();
		db_key::i()->delete('test');
		var_dump(db_key::i()->exists('test'));
		$this->out();
	}
}