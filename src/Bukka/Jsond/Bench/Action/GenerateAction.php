<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Exception\ActionException;
use Bukka\Jsond\Bench\Util\DirectorySortedIterator;

/**
 * Generator action
 */
class GenerateAction extends AbstractAction
{
    /**
     * Generate templates
     * - iterate conf sizes
     */
    public function execute() {
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $input = $this->conf->getTemplateDir() . $sizeName;
            $output = $this->conf->getOutputDir() . $sizeName;
            $count = isset($sizeConf['count']) ? $sizeConf['count'] : 1;
            $seed = isset($sizeConf['seed']) ? $sizeConf['seed'] : 1;
            $this->executeSize($input, $output, $count, $seed);
        }
    }

    /**
     * Generate instances for path
     * - create instances (count taken from config) using jsogen)
     *
     * @param string $input
     * @param string $output
     * @param int $count
     * @param int $seed
     *
     * @throws ActionException
     */
    protected function executeSize($input, $output, $count, $seed) {
        if (is_dir($input)) {
            if (!is_dir($output) && !mkdir($output)) {
                throw new ActionException("Creating directory failed");
            }
            foreach (new DirectorySortedIterator($input) as $fileInfo) {
                $fname = $fileInfo->getFilename();
                $this->executeSize("$input/$fname", "$output/$fname", $count, $seed);
            }
        } elseif ($this->conf->isWhiteListed($input, true)) {
            $filePaths = $this->createPaths($output, $seed, $count);
            if (!empty($filePaths)) {
                $this->clearExistingPaths($filePaths, $output, $count);
                $seedValue = $seed;
                foreach ($filePaths as $path) {
                    if (!file_exists($path)) {
                        $cmd = sprintf("%s %s -o %s -s %d", $this->conf->getGenerator(), $input, $path, $seedValue++);
                        system($cmd);
                    }
                }
            }
        }
    }

    /**
     * Create paths for output
     *
     * @param string $output
     * @param int    $seed
     * @param int    $count
     *
     * @return array New paths
     */
    protected function createPaths($output, $seed, $count) {
        if ($count < 1) {
            return array();
        }
        if ($count == 1) {
            return array($output);
        }
        $nameWithoutExt = substr($output, 0, strlen($output) - 5);
        $paths = array();
        for ($i = 0; $i < $count; $i++) {
            $paths[] = $nameWithoutExt . '__' . ($seed + $i) . '.json';
        }
        return $paths;
    }

    /**
     * Clear existing paths
     *
     * @param array  $newFilePaths
     * @param string $output
     * @param int    $count
     */
    protected function clearExistingPaths($newFilePaths, $output, $count)
    {
        $force = $this->conf->isForce();
        if (($force || $count > 1) && file_exists($output)) {
            unlink($output);
        }
        $pattern = substr($output, 0, strlen($output) - 5) .  '__*';
        foreach (glob($pattern) as $path) {
            if ($force || !in_array($path, $newFilePaths)) {
                $this->writeln("unlink " . $path);
                unlink($path);
            }
        }
    }
}
