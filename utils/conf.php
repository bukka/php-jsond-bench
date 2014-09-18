<?php
namespace Json\Bench;

/**
 * Config class
 */
class Conf
{
	/**
	 * Constructor
	 *
	 * @param string $confFile
	 */
	public function __construct($confFile) {
		$this->conf = json_decode(file_get_contents($confFile));
	}
}