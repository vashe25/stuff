<?php
$user = 'root';
$password = 'password';
$host = 'localhost';
$db = 'ponyexpress_index';
$date = date("Y-m-d");
$dump = $_SERVER["DOCUMENT_ROOT"] . "/upload/{$date}_{$db}.sql";
$cmd = "mysqldump --user={$user} --password={$password} --host={$host} \"{$db}\" --result-file=\"{$dump}\"";
$status = 0;
$output = [];
exec($cmd, $output, $status);
$output = implode("\n", $output);
echo "<pre>{$status}</pre>\n<pre>{$output}</pre>\n";
echo 'http://' . $_SERVER['HTTP_HOST'] . "/upload/{$date}_{$db}.sql";
