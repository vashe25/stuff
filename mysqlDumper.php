<?php
$date = date("Y-m-d");
exec("mysqldump -u{$DBLogin} -p{$DBPassword} -h{$DBHost} {$DBName} | gzip > {$_SERVER['DOCUMENT_ROOT']}/upload/dbdump_{$date}.sql.gz");
echo "{$_SERVER['HTTP_HOST']}/upload/dbdump_{$date}.sql.gz";