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

class CommandUtil {
	const COMMAND_MASK = '/%[A-Za-z0-9._]+%/';
	const VALUE_MASK = '/[A-Za-z0-9._]+/';
	
	public static function strReplaceMasks($str, $values) {
		preg_match_all(self::COMMAND_MASK, $str, $matches);

		if ($matches && count($matches)>0) {
			foreach ($matches[0] as $match) {
				$value = self::getValueFromConfig($match, $values);
				$str = str_replace($match, $value, $str);
			}
		}
		
		return $str;
	}
	
	public static function replaceMasks($commands, $values) {
		foreach ($commands as $key => $command) {
			if (is_array($command) && count($command)>0) {
				$commandsTmp = self::replaceMasks($command, $values);
				$commands[$key] = $commandsTmp;
			} else {
				if ($command)
					$commands[$key] = self::strReplaceMasks($command, $values);
			}
		}
		
		return $commands;
	}
	
	private static function getValueFromConfig($match, $values) {
		preg_match(self::VALUE_MASK, $match, $fullKey);
		
		if ($fullKey && count($fullKey)>0) {
			$keys = split('\.', $fullKey[0]); // remove % characters
			
			if ($keys && count($keys)>0) {
				foreach ($keys as $key) {
					$values = $values[$key];
				}	
			}
		}
		
		return $values;
	}
}
