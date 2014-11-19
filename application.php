<?php

use Bukka\Jsond\Bench\Command\GenCommand;

use Symfony\Component\Console\Application;

$templateDir = __DIR__ . "/templates/";
$outputDir = __DIR__ . "/output/";
$storageDir = __DIR__ . "/results/";
$benchDir = __DIR__ . "/bench/";
$confDir = __DIR__ . "/conf/";
$confFile = $confDir . "bench.json";

$conf = new Conf($argv, $confFile, $templateDir, $outputDir, $benchDir, $storageDir);

$genCommand = new GenCommand($conf);

$application = new Application();
$application->add($genCommand);
$application->run();