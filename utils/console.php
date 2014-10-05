#!/usr/bin/env php
<?php

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/bench.php';
require_once __DIR__ . '/generator.php';

use Json\Bench\Conf;
use Json\Bench\Bench;
use Json\Bench\Generator;

$templateDir = __DIR__ . "/../templates/";
$outputDir = __DIR__ . "/../output/";
$confDir = __DIR__ . "/../conf/";
$confFile = $confDir . "bench.json";

try {
    $conf = new Conf($argv, $confFile, $templateDir, $outputDir);

    if ($conf->isGen()) {
        array_shift($argv);
        $gen = new Generator($conf);
        $gen->generate();
    } else {
        $bench = new Bench($conf);
        $bench->run();
    }
} catch (\Exception $e) {
    echo $e->getMessage() . "\n";
}
