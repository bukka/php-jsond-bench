<?php

namespace Bukka\Jsond\Bench\Storage;

use Bukka\Jsond\Bench\Conf\Conf;

/**
 * Abstract Storage
 */
abstract class AbstractStorage implements StorageInterface
{
    /**
     * @var \Bukka\Jsond\Bench\Conf
     */
    protected $conf;

    /**
     *
     * @param \Bukka\Jsond\Bench\Conf $conf
     */
    public function __construct(Conf $conf)
    {
        $this->conf = $conf;
    }

    /**
     * Open storage
     */
    abstract public function open();

    /**
     * Close storage
     */
    abstract public function close();

    /**
     * Flush stored data
     */
    abstract public function flush();

    /**
     * Save parse run record
     *
     * @param array $record Record to save
     */
    abstract public function saveRecord(array $record);

    /**
     * Save run
     *
     * @param string $path   Measured file path
     * @param string $action It can be encode or decode
     * @param int    $loops  Number of loops
     * @param array  $runs   Times for the runs
     */
    public function save($path, $action, $loops, array $runs)
    {
        $dirCat = explode('/', substr(dirname($path), strlen($this->conf->getOutputDir())));
        $fileName = basename($path);
        $fileCat = substr($fileName, 0, strpos($fileName, '__'));
        $record = array(
            'action'   => $action,
            'loops'    => $loops,
            'runs'     => $runs,
            'category' => array(
                'size'      => $dirCat[0],
                'type'      => $dirCat[1],
                'structure' => $dirCat[2],
                'name'      => $fileCat,
            ),
        );
        $this->saveRecord($record);
    }
}