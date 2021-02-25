<?

class ooops_index_action extends frontend_controller
{
	public function execute()
	{
		header("HTTP/1.1 404 Not Found");
		if ($error_details = context::get('debug_error')) {
			$msg = explode('@', $error_details['message']);

			if ($error_details['data']['type'] == 'public') {
				$this->error_message = t(trim($msg[0]));
				if ($msg[1]) {
					load::model('help/info');
					if (help_info_peer::instance()->key_exists(trim($msg[1])))
						$this->error_help = trim($msg[1]);
				}
				return;
			}

			if (conf::get('debug_email_address')) {
				load::system('email/email');

				$email = new email();
				$email->setSubject(conf::get('project-name') . ': Error happened on ' . $_SERVER['SERVER_NAME'] . ', ' . date('d M, Y'));
				$email->setReceiver(conf::get('debug_email_address'));

				$error_details = context::get('debug_error');
				$body = t(trim($msg[0])) . "\n\n";
				if (session::is_authenticated()) {
					$body .= 'Пользователь: ' . user_helper::full_name(session::get_user_id(), false) . "\n";
					$body .= 'http://' . context::get('host') . '/profile-' . session::get_user_id() . "\n\n";
				}
				$body .= 'URL: ' . $_SERVER['REQUEST_URI'] . "\n\n";
				$body .= 'Referrer: ' . $_SERVER['HTTP_REFERER'] . "\n\n";
				$body .= 'File: ' . $error_details['data']['file'] . "\n";
				$body .= 'Line: ' . $error_details['data']['line'] . "\n";
				$body .= 'Code: ' . $error_details['data']['code'] . "\n";

				if ($error_details['data']['sql']) {
					$body .= 'SQL: ' . $error_details['data']['sql'] . "\n";
				}

				$body .= "\nBacktrace:\n";
				foreach ($error_details['data']['backtrace'] as $trace_data) {
					$body .= "class: {$trace_data['class']}\nmethod:{$trace_data['function']}\nfile:{$trace_data['file']} ({$trace_data['line']})\n\n";
				}
				$body .= "\n\n";
				$body .= print_r($_SERVER, 1);

				$email->setBody($body);
				$email->send(false, "debugmails");
			} else {
				error_log(print_r($error_details, 1));
			}
		}
	}
}
