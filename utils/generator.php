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
			$seed = isset($sizeConf['seed']) ? $sizeConf['seed'] : 1;
			$this->generateSize($input, $output, $count, $seed);
		}
	}

	/**
	 * Generate instances for path
	 * - create instances (count taken from config) using jsogen)
	 *
	 * @param string $input
	 * @param string $output
	 * @param int    $count
	 * @param int    $seed
	 */
	protected function generateSize($input, $output, $count, $seed) {
		if (is_dir($input)) {
			if (!is_dir($output) && !mkdir($output)) {
				throw new Exception("Creating directory failed");
			}
			foreach (new \DirectoryIterator($input) as $fileInfo) {
				if (!$fileInfo->isDot()) {
					$fname = $fileInfo->getFilename();
					$this->generateSize("$input/$fname", "$output/$fname", $count, $seed);
				}
			}
		} else {
			$filePaths = $this->createPaths($output, $seed, $count);
			if (!empty($filePaths)) {
				$this->clearExistingPaths($filePaths, $output, $count);
				$seedValue = $seed;
				foreach ($filePaths as $path) {
					$cmd = sprintf("%s %s -o %s -s %d", $this->conf->getGenerator(), $input, $path, $seedValue++);
					echo $cmd . "\n";
				}
			}
		}
	}

	/**
	 * Create paths for output
	 *
	 * @param string $output
	 * @param int    $seed
	 * @param int    $count
	 *
	 * @return array New paths
	 */
	protected function createPaths($output, $seed, $count) {
		if ($count < 1) {
			return array();
		}
		if ($count == 1) {
			return array($output);
		}
		$nameWithoutExt = substr($output, 0, strlen($output) - 5);
		$paths = array();
		for ($i = 0; $i < $count; $i++) {
			$paths[] = $nameWithoutExt . '__' . ($seed + $i) . '.json';
		}
		return $paths;
	}

	/**
	 * Clear existing paths
	 *
	 * @param array  $newFilePaths
	 * @param string $output
	 * @param int    $count
	 * @param int    $force
	 */
	protected function clearExistingPaths($newFilePaths, $output, $count, $force = false)
	{
		if (($force || $count > 1) && file_exists($output)) {
			unlink($output);
		}
		$nameWithoutExt = substr($output, 0, strlen($output) - 5);
		foreach (new \GlobIterator($nameWithoutExt . '__*') as $fileInfo) {
			if ($force || !in_array($fileInfo->getPath(), $newFilePaths)) {
				unlink($fileInfo->getPath());
			}
		}
	}
}
