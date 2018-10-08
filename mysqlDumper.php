<?php
$date = date("Y-m-d");
exec("mysqldump -u{$DBLogin} -p{$DBPassword} -h{$DBHost} {$DBName} | gzip > {$_SERVER['DOCUMENT_ROOT']}/upload/{$date}_{$DBName}.gz");