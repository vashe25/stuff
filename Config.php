<?php
/**
 * Created by PhpStorm.
 * User: vashe
 * Date: 9/15/18
 * Time: 1:16 AM
 */

namespace vashe;


class Config
{
	protected $conf;

	public function __construct()
	{
		$this->conf = $this->loadConfig();
		if ($this->conf == false) {
			throw new \Exception('Config file does not exist');
		}
	}

	public function getConfig($row)
	{
		if (array_key_exists($row, $this->conf)) {
			return $this->conf[$row];
		}
		return false;
	}

	protected function getConfigPath()
	{
		return dirname(__FILE__) . '/conf.php';
	}

	protected function loadConfig()
	{
		$configPath = $this->getConfigPath();
		if (file_exists($configPath)) {
			return require_once $configPath;
		}
		return false;
	}


}