<?php

namespace Bukka\Jsond\Bench\Writer;

abstract class AbstractWriter implements WriterInterface
{
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
}