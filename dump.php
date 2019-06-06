<?php
namespace vashe;

$cf = dirname(__FILE__) . '/';
$required = [
	'Commander.php',
	'Config.php',
	'Dumper.php'
	];

foreach ($required as $class) {
	require_once $cf . $class;
}

new Commander();