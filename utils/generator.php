<?php

namespace Json\Bench;

use Json\Bench\Conf;

/**
 * Generator of benchmark instances
 */
class Generator
{
	/**
	 * Main configuration
	 * 
	 * @var \Json\Bench\Conf 
	 */
	protected $conf;

	/**
	 * Constructor
	 * 
	 * @param \Json\Bench\Conf $conf
	 */
	public function __construct(Conf $conf) {
		$this->conf = $conf;
	}

	/**
	 * Generate templates
	 * - iterate conf sizes
	 */
	public function generate() {
		foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
			$input = $this->conf->getTemplateDir() . $sizeName;
			$output = $this->conf->getOutputDir() . $sizeName;
			$count = isset($sizeConf['count']) ? $sizeConf['count'] : 1;
			$this->generateSize($input, $output, $count);
		}
	}

	/**
	 * Generate instances for path
	 * - create instances (count taken from config) using jsogen)
	 *
	 * @param string $input
	 * @param string $output
	 * @param int $count
	 */
	protected function generateSize($input, $output, $count) {
		if (is_dir($input)) {
			if (!is_dir($output) && !mkdir($output)) {
				throw new Exception("Creating directory failed");
			}
			foreach (new \DirectoryIterator($input) as $fileInfo) {
				if (!$fileInfo->isDot()) {
					$fname = $fileInfo->getFilename();
					$this->generateSize("$input/$fname", "$output/$fname", $count);
				}
			}
		} else {
			$cmd = sprintf("%s %s -o %s -s %d", $this->conf->getGenerator(), $input, $output, $count);
			echo $cmd . "\n";
		}
	}
}
