<?php

/**
* Переводит строку с кило/мега/гигабайтами в байты
* @param $sizeStr
* @return int
*/

function returnBytes($sizeStr)
{
	switch (substr ($sizeStr, -1)) {
		case 'M': case 'm': return (int)$sizeStr * 1048576;
		case 'K': case 'k': return (int)$sizeStr * 1024;
		case 'G': case 'g': return (int)$sizeStr * 1073741824;
		default: return $sizeStr;
	}
}

Не более <?= ini_get('max_file_uploads') ?> файлов, объемом не более <?= floor((min(returnBytes(ini_get('post_max_size')), returnBytes(ini_get('upload_max_filesize'))) - 5*1024*1024) / 1048576) ?> МБ


