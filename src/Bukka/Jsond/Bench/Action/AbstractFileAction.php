<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Util\DirectorySortedIterator;

/**
 * Templates checking class
 */
abstract class AbstractFileAction extends AbstractAction
{
    /**
     * Execute action for each size
     */
    public function execute()
    {
        $this->preExecute();
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $this->executeSize($output, $sizeConf);
        }
        $this->postExecute();
    }

    /**
     * Called before execution
     */
    protected function preExecute()
    {
        // nothing by default
    }

    /**
     * Called after execution
     */
    protected function postExecute()
    {
        // nothing by default
    }

    /**
     * Execute action for each file
     *
     * @param string $path
     * @param array  $sizeConf
     */
    protected function executeSize($path, $sizeConf)
    {
        if (is_dir($path)) {
            foreach (new DirectorySortedIterator($path) as $fileInfo) {
                $fileName = $fileInfo->getFilename();
                $this->executeSize("$path/$fileName", $sizeConf);
            }
        } elseif (is_file($path) && $this->conf->isWhiteListed($path, false)) {
            $this->executeFile($path, $sizeConf);
        }
    }

    /**
     * Execute file output instance
     *
     * @param $path
     * @param $sizeConf
     */
    abstract protected function executeFile($path, $sizeConf);
}