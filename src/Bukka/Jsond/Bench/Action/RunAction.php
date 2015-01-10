<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Util\DirectorySortedIterator;
use Bukka\Jsond\Bench\Writer\WriterInterface;

/**
 * Benchmark run class
 */
class RunAction extends AbstractAction
{
    /**
     * Storage
     *
     * @var \Bukka\Jsond\Bench\Storage\StorageInterface
     */
    protected $storage;

    /**
     * Storage
     *
     * @var \Bukka\Jsond\Bench\Storage\StorageInterface
     */
    protected $types;


    /**
     * Constructor
     *
     * @param Conf            $conf
     * @param WriterInterface $writer
     */
    public function __construct(Conf $conf, WriterInterface $writer) {
        parent::__construct($conf, $writer);
        $this->storage = $conf->getStorage();
        $this->types = $conf->getRunTypes();
        $this->actions = $conf->getRunActions();
    }

    /**
     * Run benchmark
     * - walk output dir
     * - measure time json_decode and jsond_decode
     * - measure time json_encode and jsond_encode
     * - print results
     */
    public function execute() {
        $this->storage->open();
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $loops = isset($sizeConf['loops']) ? $sizeConf['loops'] : 1;
            $this->executeSize($output, $loops);
        }
        $this->storage->close();
    }

    /**
     * Run benchmark for size
     *
     * @param string $path
     * @param int    $loops
     */
    protected function executeSize($path, $loops) {
        if (is_dir($path)) {
            foreach (new DirectorySortedIterator($path) as $fileInfo) {
                $fname = $fileInfo->getFilename();
                $this->executeSize("$path/$fname", $loops);
            }
        } elseif ($this->conf->isWhiteListed($path, false)) {
            $this->benchFile($path, $loops);
        }
    }

    /**
     * Bench file instance
     *
     * @param string $path
     * @param int $loops
     */
    protected function benchFile($path, $loops) {
        $this->writer->formatLine("FILE: %s", $path);

        foreach ($this->actions as $action) {
            $result = array();
            foreach ($this->types as $type) {
                $result[$type] = $this->bench($type, $action, $path, $loops);
            }
            $this->printBenchInfo($action, $result);
            $this->storage->save($path, $action, $loops, $result);
        }
    }

    /**
     * Bench file
     *
     * @param string $type
     * @param string $action
     * @param string $path
     * @param int    $loops
     *
     * @return float
     */
    protected function bench($type, $action, $path, $loops) {
        $bench = $this->conf->getBenchDir() . "$type/$action.php";
        $command = "php $bench $path $loops";
        $result = json_decode(exec($command));
        return isset($result->time) ? $result->time : 0;
    }


    /**
     * Print bench info
     *
     * @param string $action
     * @param array  $results
     */
    protected function printBenchInfo($action, $results) {
        $resultStrings = array();
        foreach ($results as $type => $result) {
            $resultStrings[] = sprintf("%s: %.6f", $type, $result);
        }
        $this->writer->formatLine(" %s: %s", strtoupper($action), implode(' :: ', $resultStrings));
    }
}
