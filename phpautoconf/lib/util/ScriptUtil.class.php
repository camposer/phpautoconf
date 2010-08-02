<?php
/* 
    This file is part of phpautoconf.

    phpautoconf is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    phpautoconf is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
*/

class ScriptUtil {
	const TYPE_SHELL = 'shell';
	const TYPE_PROCESS = 'process';
	private $commands;
	private $processes;
	private $pipes;
	
	function __construct($commands) {
		$this->commands = $commands;
	}
	
	public function getCommands() {
		return $this->commands;
	}
	
	public function init() {
		// initializing processes
		if (isset($this->commands['processes'])) {
			$configProcesses = $this->commands['processes'];
			
			$ds = array(
			       0 => array("pipe", "r"),   // stdin
			       1 => array("pipe", "w"),  // stdout
			       2 => array("pipe", "w")   // stderr
			); // descriptorspec
			
			foreach($configProcesses as $process => $config) {
				$this->processes[$process] = proc_open($config['command'], $ds, $this->pipes[$process]);
				echo "Opened: $process\n";				
			}

			echo "\n";
		}
	}

	public function finalize() {
		if ($this->processes) {
			foreach($this->processes as $process => $value) {
				proc_close($value);
				echo "Closed: $process\n";
			}
		}		
	}
	
	public function execute($task, &$output='', &$return='', $params='') {
		// printing task in green
		echo "\033[32m" . $task . ":\033[0m\n";

		if (isset($this->commands['tasks'])) {
			$configTasks = $this->commands['tasks'];
			
			if (isset($configTasks[$task]))
			foreach($configTasks[$task] as $key => $action) {
				$command = $action['command'];
				$process = '';
				
				// replacing params (in case exists)
				if ($params) {
					foreach($params as $param => $value) {
						$command = str_replace("?$param?", $value, $command);
					}
				}
				
				// executing action
				if ($action['type'] == self::TYPE_PROCESS) {
					$process = $action['process'];
					$pipe = $this->pipes[$process];

					fwrite($pipe[0], $command); // sending input and getting status
					$status = proc_get_status($this->processes[$process]);
					$return = $status['exitcode'];
					
					//fflush($pipe[0]);
					//fclose($pipe[0]);
					
					//$output = stream_get_contents($pipe[1]); // getting output
					//fclose($pipe[1]);					
				} else if ($action['type'] == self::TYPE_SHELL) {
					exec($command, $output, $return);
				}

				// writting command and status
				if ($process)
					$process.=': ';

				echo "[$return] $process" . $command . "\n";
			}
		}
	}
	
}
