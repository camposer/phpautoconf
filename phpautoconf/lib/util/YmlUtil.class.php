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

class YmlUtil {
	/**
	 * Loads a yml file and returns its corresponding array
	 * @param $file
	 * @return array Loades yml as an array
	 */
	public static function load($file) {
		$loadedYml = null;
		$yaml = new sfYamlParser();

		try {
			$loadedYml = $yaml->parse(file_get_contents($file));
		} catch (InvalidArgumentException $e) {
			// an error occurred during parsing
			echo "Unable to parse the YAML string: ".$e->getMessage();
		}
		
		return $loadedYml;
	}
	
	
}