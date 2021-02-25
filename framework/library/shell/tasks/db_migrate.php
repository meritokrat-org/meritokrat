<?

class db_migrate_task extends shell_task
{
	public function execute()
	{
		$is_mock = in_array('mock', array_keys($this->arguments));

		if ( $is_mock )
		{
			$this->notice('Running in mock mode');
		}

		$this->start_from = (int)$this->arguments['from'];

		$sqls = conf::get('project_root') . '/data/sql';
		$history_file = conf::get('project_root') . '/data/sql/history';
		
		$migrations = glob("{$sqls}/*.sql");
		sort($migrations);
		
		$history = explode("\n", @file_get_contents($history_file));
		
		$executed_count = 0;
		
		foreach ( $migrations as $migration )
		{
			if ( $this->start_from )
			{
				preg_match('/\/([0-9]+)_.+/', $migration, $m);
				$index = (int)$m[1];

				if ( $index < $this->start_from ) continue;
			}
			else if ( in_array(basename($migration), $history) )
			{
				continue;
			}
			
			$this->out( "\n\n" . 'Migration: ' . basename($migration), 'brown' );
			
			$sql = file_get_contents( $migration );
			foreach ( explode(';', $sql) as $sql_instruction )
			{
				if ( $sql_instruction = trim($sql_instruction) )
				{
					if ( strpos($sql_instruction, '--') === 0 ) continue;

					$start_ts = microtime(true);
					$this->out($sql_instruction, 'silver');
					if ( !$is_mock )
					{
						db::exec($sql_instruction);
						$this->out('Executed in ' . ( ceil( (microtime(true) - $start_ts)*10000 )/10000) . ' second', 'green' );
					}
					
					$executed_count++;
				}
			}
			
			$history[] = basename($migration);
		}
		
		if ( $executed_count )
		{
			file_put_contents($history_file, implode("\n", $history));
			
			$this->out();
			$this->out('History written', 'green');
		}
		else
		{
			$this->out('Nothing to migrate', 'purple');
		}
	}
}