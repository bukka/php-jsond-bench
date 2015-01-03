<?php

namespace Bukka\Jsond\Bench\Writer;

interface WriterInterface
{
    /**
     * Write message
     *
     * @param $message
     *
     * @return null
     */
    public function write($message);

    /**
     * Write message and NL
     *
     * @param $message
     *
     * @return null
     */
    public function writeLine($message);

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
}