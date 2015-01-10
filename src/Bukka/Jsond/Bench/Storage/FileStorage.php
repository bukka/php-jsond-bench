<?php

namespace Bukka\Jsond\Bench\Storage;

use Bukka\Jsond\Bench\Util\DirectorySortedIterator;

/**
 * File Storage
 */
class FileStorage extends AbstractStorage
{
    /**
     * Result file name
     */
    const RESULT_FILE_NAME = 'result.json';

    /**
     * @var \DateTime
     */
    protected $openTime;

    /**
     * @var array
     */
    protected $records = array();

    /**
     * Return result file name from dir
     *
     * @param string $dirPath
     *
     * @return string
     */
    protected function getResultFileName($dirPath)
    {
        return $dirPath . "/" . self::RESULT_FILE_NAME;
    }


    /**
     * Get result path from date time
     *
     * @param mixed   $dateTime
     * @param boolean $dirOnly  Return directory only
     *
     * @return string Path
     */
    protected function getResultPathFromDate($dateTime, $dirOnly = false)
    {
        if (!$dateTime instanceof \DateTime) {
            $dateTime = new \DateTime(preg_replace("/[^0-9:-]/", '', $dateTime));
        }

        $dirPath = $this->conf->getStorageDir() . $dateTime->format("Ymd/His");
        if ($dirOnly) {
            return $dirPath;
        }

        return $this->getResultFileName($dirPath);
    }

    /**
     * Get latest result path
     *
     * @return string Path
     */
    protected function getLatestResultPath()
    {
        $storageDirIterator = new DirectorySortedIterator($this->conf->getStorageDir());
        $lastStorageDir = $storageDirIterator->last();
        if (is_null($lastStorageDir)) {
            return null;
        }

        $lastStorageDirIterator = new DirectorySortedIterator($lastStorageDir);
        $lastDir = $lastStorageDirIterator->last();
        if (is_null($lastDir)) {
            return null;
        }

        return $this->getResultFileName($lastDir->getPathname());
    }


    /**
     * Open storage
     */
    public function open()
    {
        $this->openTime = new \DateTime();
        $this->records = array();
    }

    /**
     * Close storage
     */
    public function close()
    {
        $this->flush();
    }

    /**
     * Flush stored data
     */
    public function flush()
    {
        $dirPath = $this->getResultPathFromDate($this->openTime, true);
        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true)) {
            throw new \Exception("Creating dir $dirPath failed");
        }
        $filePath = $this->getResultFileName($dirPath);
        file_put_contents($filePath, json_encode($this->records, JSON_PRETTY_PRINT));
    }


    /**
     * Load stored data
     *
     * @param mixed $dateTime Date time for loaded instance
     *
     * @return array
     */
    public function load($dateTime = 'latest')
    {
        $path = (strlen($dateTime) === 0 || $dateTime === 'latest') ?
            $this->getLatestResultPath() :
            $this->getResultPathFromDate($dateTime);
        if (is_null($path)) {
            return null;
        }

        return json_decode(file_get_contents($path));
    }

    /**
     * Save parse run record
     *
     * @param array $record Record to save
     */
    public function saveRecord(array $record)
    {
        $this->records[] = $record;
    }
}