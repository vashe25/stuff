<?php
/**
 * Created by PhpStorm.
 * User: vashe
 * Date: 9/15/18
 * Time: 1:50 AM
 */

namespace vashe;


class Dumper
{
	protected $fields = [
		'user',
		'password',
		'host',
	];

	public function dump($conf) {
		$cmd = $this->makeCmd($conf);
		$output = [];
		exec($cmd,$output);
		if (empty($output)) {
			return false;
		}
		return implode("\n", $output);
	}

	protected function makeResultFile($dumpName) {
		$today = date('Y-m-d');
		return dirname(__FILE__) . "/dumps/{$today}-{$dumpName}.sql";
	}

	protected function makeCmd($conf)
	{
		$cmd = ['mysqldump'];
		foreach ($this->fields as $f) {
			if (!empty($conf[$f]))
				$cmd[] = "--{$f}=" . $conf[$f];
		}
		$cmd[] = $conf['database'];
		$cmd[] = '--result-file=' . $this->makeResultFile($conf['dump']);
		return implode(' ', $cmd);
	}
}