$res = [];
echo exec("ls -la /etc", $res);

foreach ($res as $r) {
	echo $r . "\n";
}
