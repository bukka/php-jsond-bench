<?php

namespace Bukka\Jsond\Bench\Action;

/**
 * Templates checking class
 */
class CheckAction extends AbstractAction
{
    /**
     * Test templates
     */
    public function execute() {
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $this->executeSize($output);
        }
    }

    /**
     * Test for size
     *
     * @param string $output
     */
    protected function executeSize($path) {
        if (is_dir($path)) {
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if (!$fileInfo->isDot()) {
                    $fname = $fileInfo->getFilename();
                    $this->executeSize("$path/$fname");
                }
            }
        } elseif ($this->conf->isWhiteListed($path, false)) {
            $this->testFile($path);
        }
    }

    /**
     * Bench file instance
     *
     * @param string $path
     */
    protected function testFile($path) {
        printf("FILE: %s\n", $path);
        $string = file_get_contents($path);
        printf("LENGTH: %s\n", strlen($string));
        // Decoding
        $decodeTestResult = $this->testDecode($string);
        printf("DECODING: %s\n", $decodeTestResult);
        // Encoding
        $encodeTestResult = $this->testEncode(json_decode($string));
        printf("ENCODING: %s\n\n", $encodeTestResult);
    }

    /**
     * Test decoding
     *
     * @param string $string
     *
     * @return string
     */
    protected function testDecode($string) {
        $json = json_decode($string);
        $jsond = jsond_decode($string);
        if (is_null($json) && is_null($jsond)) {
            return 'EE';
        }
        if (is_null($json)) {
            return 'ES';
        }
        if (is_null($jsond)) {
            return 'SE';
        }
        // todo: deep comparison
        return 'SS';
    }

    /**
     * Test encoding
     *
     * @param string $string
     *
     * @return string
     */
    protected function testEncode($object) {
        $json = json_encode($object);
        $jsond = jsond_encode($object);
        if ($json === $jsond) {
            return is_null($json) ? 'EE' : 'SS';
        }
        if (is_null($json)) {
            return 'ES';
        }
        if (is_null($jsond)) {
            return 'SE';
        }
        return 'NN';
    }
}
