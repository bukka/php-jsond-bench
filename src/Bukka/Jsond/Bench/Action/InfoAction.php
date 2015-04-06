<?php

namespace Bukka\Jsond\Bench\Action;

/**
 * File checking class
 */
class InfoAction extends AbstractFileAction
{
    /**
     * Execute check of file instance
     *
     * @param string $path
     * @param array $sizeConf
     */
    protected function executeFile($path, $sizeConf)
    {
        $this->printf("FILE: %s\n", $path);
        $string = file_get_contents($path);
        $this->printf("LENGTH: %s\n", strlen($string));
    }
}