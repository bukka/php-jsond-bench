<?php

namespace Bukka\Jsond\Bench\Storage;

/**
 * File Storage
 */
class FileStorage extends AbstractStorage
{
    /**
     * @var \DateTime
     */
    protected $openTime;

    /**
     * @var array
     */
    protected $records = array();

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
        $dirPath = $this->conf->getStorageDir() . $this->openTime->format("Ymd_his");
        if (!is_dir($dirPath) && !mkdir($dirPath, 0777, true)) {
            throw new \Exception("Creating dir $dirPath failed");
        }
        $filePath = $dirPath . "/result.json";
        file_put_contents($filePath, json_encode($this->records));
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