<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Writer\WriterInterface;

/**
 * Benchmark run class
 */
class RunAction extends AbstractFileAction
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
     * Actions
     *
     * @var array
     */
    protected $actions;

    /**
     * @var boolean
     */
    protected $save;

    /**
     * Constructor
     *
     * @param Conf            $conf
     * @param WriterInterface $writer
     */
    public function __construct(Conf $conf, WriterInterface $writer)
    {
        parent::__construct($conf, $writer);
        $this->storage = $conf->getStorage();
        $this->types = $conf->getRunTypes();
        $this->actions = $conf->getRunActions();
        $this->save = $conf->getParam('save');
    }

    /**
     * Called before execution
     */
    protected function preExecute()
    {
        if ($this->save) {
            $this->storage->open();
        }
    }

    /**
     * Called after execution
     */
    protected function postExecute()
    {
        if ($this->save) {
            $this->storage->close();
        }
    }

    /**
     * Execute benchmarking of file instance
     *
     * @param string $path
     * @param array  $sizeConf
     */
    protected function executeFile($path, $sizeConf)
    {
        $this->writer->formatLine("FILE: %s", $path);

            foreach ($this->actions as $action) {
            $result = array();
            foreach ($this->types as $type) {
                $result[$type] = $this->bench($type, $action, $path, $sizeConf['loops']);
            }
            $this->printBenchInfo($action, $result);
            if ($this->save) {
                $this->storage->save($path, $action, $sizeConf['loops'], $result);
            }
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
    protected function bench($type, $action, $path, $loops)
    {
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
    protected function printBenchInfo($action, $results)
    {
        $resultStrings = array();
        foreach ($results as $type => $result) {
            $resultStrings[] = sprintf("%s: %.6f", $type, $result);
        }
        $this->writer->formatLine(" %s: %s", strtoupper($action), implode(' :: ', $resultStrings));
    }
}
