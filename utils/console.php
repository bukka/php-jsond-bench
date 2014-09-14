#!/usr/bin/env php
<?php

use Json\Bench\Conf;
use Json\Bench\Bench;

require_once __DIR__ . '/conf.php';
require_once __DIR__ . '/bench.php';

$templateDir = __DIR__ . "/../templates/";
$confDir = __DIR__ . "/../conf/";
$confFile = $confDir . "bench.json";

$conf = new Conf($confFile);
$bench = new Bench($conf);
$bench->run();