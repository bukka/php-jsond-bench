<?php

namespace Bukka\Jsond\Bench\Storage;

/**
 * Storage interface
 */
interface StorageInterface
{
    /**
     * Open storage
     */
    public function open();

    /**
     * Close storage
     */
    public function close();

    /**
     * Flush stored data
     */
    public function flush();

    /**
     * Load stored data
     *
     * @param mixed $dateTime Date time for loaded instance
     *
     * @return array
     */
    public function load($dateTime = 'latest');

    /**
     * Save run
     *
     * @param string $path   Measured file path
     * @param string $action It can be encode or decode
     * @param int    $loops  Number of loops
     * @param array  $runs   Times for the runs
     */
    public function save($path, $operation, $loops, array $runs);
}