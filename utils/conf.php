<?php
namespace Json\Bench;

/**
 * Config class
 */
class Conf
{
	/**
	 * Default generator executable
	 */
	const DEFAULT_GENERATOR = 'jsogen';

	/**
	 * Config array
	 *
	 * @var array
	 */
	protected $conf;

	/**
	 * Template directory
	 *
	 * @var string
	 */
	protected $templateDir;

	/**
	 * Output directory
	 *
	 * @var string
	 */
	protected $outputDir;

	/**
	 * Constructor
	 *
	 * @param string $confFile
	 */
	public function __construct($confFile, $templateDir, $outputDir) {
		$this->conf = json_decode(file_get_contents($confFile));
		$this->templateDir = $templateDir;
		$this->outputDir = $outputDir;
	}

	/**
	 * Magic getter
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function __get($name) {
		switch ($name) {
			case 'generator':
				return $this->getGenerator();
			case 'sizes':
				return $this->getSizes();
			case 'td':
				return $this->templateDir;
			case 'od':
				return $this->outputDir;
		}
		return null;
	}

	/**
	 * Get generator executable
	 *
	 * @return string
	 */
	public function getGenerator() {
		return isset($this->conf['generator']) ? $this->conf['generator'] : self::DEFAULT_GENERATOR;
	}

	/**
	 * Get sizes
	 * 
	 * @return array
	 */
	public function getSizes() {
		return isset($this->conf['sizes']) ? $this->conf['sizes'] : array();
	}

	/**
	 * Get template directory
	 *
	 * @return string
	 */
	public function getTemplateDir() {
		return $this->templateDir;
	}

	/**
	 * Get template directory
	 *
	 * @return string
	 */
	public function getOutputDir() {
		return $this->outputDir;
	}
}
