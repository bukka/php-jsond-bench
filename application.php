<?php

require_once __DIR__ . '/bootstrap.php';

use Bukka\Jsond\Bench\Command\CheckCommand;
use Bukka\Jsond\Bench\Command\GenerateCommand;
use Bukka\Jsond\Bench\Command\InfoCommand;
use Bukka\Jsond\Bench\Command\RunCommand;
use Bukka\Jsond\Bench\Command\ViewCommand;
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
$generateCommand = new GenerateCommand($conf);
$runCommand = new RunCommand($conf);
$viewCommand = new ViewCommand($conf);
$infoCommand = new InfoCommand($conf);

$application = new Application();
$application->add($checkCommand);
$application->add($generateCommand);
$application->add($runCommand);
$application->add($viewCommand);
$application->add($infoCommand);
$application->run();