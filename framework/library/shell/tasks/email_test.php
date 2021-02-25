<?

class email_test_task extends shell_task
{
	public function execute()
	{
		if ( !$this->arguments[0] )
		{
			$this->out('Specify an email', 'red');
			return;
		}

		load::system('email/email');

		$email = new email();
		$email->setReceiver( $this->arguments[0] );

		$email->setBody('Привет, Testing body!');
		$email->setSubject('Привет, Email testing');

		$email->send();
	}
}