<?php

namespace Bukka\Jsond\Bench\Writer;

interface WriterInterface
{
    /**
     * Write message
     *
     * @param string $message
     *
     * @return null
     */
    public function write($message = '');

    /**
     * Write message and NL
     *
     * @param string $message
     *
     * @return null
     */
    public function writeLine($message = '');

    /**
     * Print formatted string
     *
     * @param $fmt
     *
     * @return null
     */
    public function format($fmt);

    /**
     * Print formatted string and NL
     *
     * @param $fmt
     *
     * @return null
     */
    public function formatLine($fmt);

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return mixed
     */
    public function setLevel($level);

    /**
     * Increment level
     *
     * @return null
     */
    public function incLevel();

    /**
     * Decrement level
     *
     * @return null
     */
    public function decLevel();
}