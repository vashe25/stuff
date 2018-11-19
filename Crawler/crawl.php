<?php
$links = require __DIR__ . '/links.php';
$result = [];
$ch = curl_init();
curl_setopt($ch, CURLOPT_NOBODY  , true);
foreach ($links as $link) {
	curl_setopt($ch, CURLOPT_URL, $link);
	curl_exec($ch);

	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	if ($httpcode != 200)
		$result[] = [
			'code' => $httpcode,
			'link' => $link
		];
}
curl_close($ch);

$fp = fopen(__DIR__ . '/links_res.txt', 'w');
fputcsv($fp, ['code', 'link']);
foreach ($result as $row) {
	fputcsv($fp, $row);
}
fclose($fp);
echo "job done\n";