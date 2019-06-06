<?php
/**
 * Created by PhpStorm.
 * User: vashe
 * Date: 9/15/18
 * Time: 2:24 AM
 */

namespace vashe;


class Commander
{
	protected $commands = [
		'short' => 'd:',
		'long' => ['dump:']
	];

	public function __construct()
	{
		$options = $this->getOptions();
		if (is_array($options) && !empty($options)) {
			$confType = (array_key_exists('d', $options)) ? $options['d'] : $options['dump'];
			$config = new Config();
			$dumpConf = $config->getConfig($confType);
			$dumper = new Dumper();
			$output = $dumper->dump($dumpConf);
			echo $output;
		} else {
			echo $this->printDefaults();
		}

	}

	protected function getOptions()
	{
		return getopt($this->commands['short'], $this->commands['long']);
	}

	protected function printDefaults()
	{
		$config = new Config();
		return implode("\n", $config->getConfig('ponyexpress_index'));
	}
}