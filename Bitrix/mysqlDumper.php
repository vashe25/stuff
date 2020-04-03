<?php
exec("mysqldump -u{$DBLogin} -p{$DBPassword} -h{$DBHost} --databases {$DBName} | gzip > {$_SERVER['DOCUMENT_ROOT']}/upload/{$DBName}.sql.gz");
echo "{$_SERVER['HTTP_HOST']}/upload/{$DBName}.sql.gz";

// unlink("{$_SERVER['DOCUMENT_ROOT']}/upload/{$DBName}.sql.gz");





echo "mysqldump -u{$DBLogin} -p{$DBPassword} -h{$DBHost} --databases {$DBName} > {$DBName}.sql";