<?php
namespace Json\Bench;

/**
 * Config class
 */
class Conf
{
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
	 * Constructor
	 *
	 * @param string $confFile
	 */
	public function __construct($confFile, $templateDir) {
		$this->conf = json_decode(file_get_contents($confFile));
		$this->templateDir = $templateDir;
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
			case 'sizes':
				return $this->getSizes();
			case 'td':
				return $this->templateDir;
		}
		return null;
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
}
