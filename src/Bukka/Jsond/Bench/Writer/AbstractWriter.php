<?php

namespace Bukka\Jsond\Bench\Writer;

abstract class AbstractWriter implements WriterInterface
{
    /**
     * Level
     *
     * @var integer
     */
    protected $level = 0;

    /**
     * Print formatted string
     *
     * @param $fmt
     *
     * @return null
     */
    public function format($fmt)
    {
        $this->write(call_user_func_array("sprintf", func_get_args()));
    }

    /**
     * Print formatted string and NL
     *
     * @param $fmt
     *
     * @return null
     */
    public function formatLine($fmt)
    {
        $this->writeLine(call_user_func_array("sprintf", func_get_args()));
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return null
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * Increment level
     *
     * @return null
     */
    public function incLevel()
    {
        $this->setLevel($this->level + 1);
    }

    /**
     * Decrement level
     *
     * @return null
     */
    public function decLevel()
    {
        $this->setLevel($this->level - 1);
    }
}