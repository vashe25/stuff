<?php


$res = [];
exec("ls {$_SERVER['DOCUMENT_ROOT']}/ | grep '.php'", $res);

foreach ($res as $r) {
	echo $r . "\n";
}