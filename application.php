<?php

require_once __DIR__ . '/vendor/autoload.php';

use Bukka\Jsond\Bench\Command\CheckCommand;
use Bukka\Jsond\Bench\Command\GenCommand;
use Bukka\Jsond\Bench\Command\RunCommand;
use Bukka\Jsond\Bench\Conf\Conf;

use Symfony\Component\Console\Application;

$templateDir = __DIR__ . "/templates/";
$outputDir = __DIR__ . "/output/";
$storageDir = __DIR__ . "/results/";
$benchDir = __DIR__ . "/bench/";
$confDir = __DIR__ . "/conf/";
$confFile = $confDir . "bench.json";

$conf = new Conf($confFile, $templateDir, $outputDir, $benchDir, $storageDir);

$checkCommand = new CheckCommand($conf);
$genCommand = new GenCommand($conf);
$runCommand = new RunCommand($conf);

$application = new Application();
$application->add($checkCommand);
$application->add($genCommand);
$application->add($runCommand);
$application->run();