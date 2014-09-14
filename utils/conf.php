<?php
namespace Json\Bench;

class Conf
{	
	public function __construct($confFile) {
		$this->conf = json_decode(file_get_contents($confFile));
	}
}