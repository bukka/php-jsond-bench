#!/usr/bin/env php
<?php

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/bench.php';
require_once __DIR__ . '/generator.php';

use Json\Bench\Conf;
use Json\Bench\Bench;
use Json\Bench\Generator;

$templateDir = __DIR__ . "/../templates/";
$confDir = __DIR__ . "/../conf/";
$confFile = $confDir . "bench.json";

$conf = new Conf($confFile);

if ($argc > 1 && strpos($argv[1], 'g') === 0) {
	$gen = new Generator($conf);
	echo 'te';
	$gen->run();
} else {
	$bench = new Bench($conf);
	$bench->run();
}