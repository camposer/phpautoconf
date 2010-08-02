<?php
//print_r($script->getCommands);exit;

$script->execute('create_replication_user', $output, $return);
$script->execute('create_dump', $output, $return);
$rows = split("\t", $output[1]);
$params['logFile'] = $rows[0];
$params['logPos'] = $rows[1];
$script->execute('restore_dump', $output, $return, $params);
$script->execute('reset_slave', $output, $return, $params);
$script->execute('set_master', $output, $return, $params);
$script->execute('start_slave', $output, $return, $params);
