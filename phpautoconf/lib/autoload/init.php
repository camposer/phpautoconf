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

require_once($baseDir . '/../lib/fabpot-yaml/lib/sfYamlParser.php');
require_once($baseDir . '/../lib/util/CommandUtil.class.php');
require_once($baseDir . '/../lib/util/YmlUtil.class.php');
require_once($baseDir . '/../lib/util/ScriptUtil.class.php');

/************ Loading configuration ************/

//echo dirname($argv[0]);
$config = YmlUtil::load($conf);
$commands = YmlUtil::load($cmd);

if ( !$config || !$commands ) {
	echo "You must have a config.yml and command.yml into your configuration\n";
	exit;
}

$commands = CommandUtil::replaceMasks($commands, $config);
$script = new ScriptUtil($commands);
$script->init();
