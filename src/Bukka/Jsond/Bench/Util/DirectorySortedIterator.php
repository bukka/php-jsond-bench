<?php

namespace Bukka\Jsond\Bench\Util;

/**
 * Sorted iterator class
 */
class DirectorySortedIterator implements \IteratorAggregate, \Countable
{
    /**
     * Sorted directories
     *
     * @var array
     */
    protected $dirs;

    /**
     * Class contructor
     *
     * @param mixed $directory Directory path
     */
    public function __construct($directory)
    {
        $dirs = array();

        if ($directory instanceof \SplFileInfo) {
            $directory = $directory->getPathname();
        }

        foreach (new \DirectoryIterator($directory) as $fileInfo) {
            if (!$fileInfo->isDot()) {
                $dirs[$fileInfo->getFilename()] = clone $fileInfo;
            }
        }

        ksort($dirs);
        $this->dirs = array_values($dirs);
    }

    /**
     * Get number of directories
     *
     * @return integer
     */
    public function count()
    {
        return count($this->dirs);
    }

    /**
     * Get first directory
     *
     * @return \SplFileInfo
     */
    public function first()
    {
        return empty($this->dirs) ? null : $this->dirs[0];
    }

    /**
     * Get last directory
     *
     * @return \SplFileInfo
     */
    public function last()
    {
        return empty($this->dirs) ? null : $this->dirs[count($this->dirs) - 1];
    }

    /**
     * Get iterator - \IteratorAggregate interface method
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->dirs);
    }
}