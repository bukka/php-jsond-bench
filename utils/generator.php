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
	 * - walk template dir
	 * - create instances (count taken from config) using jsogen)
	 */
	public function generate() {
		
	}
}
