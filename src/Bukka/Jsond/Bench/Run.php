<?php

namespace Bukka\Jsond\Bench;

/**
 * Benchmark class
 */
class Run
{
    /**
     * Main configuration
     *
     * @var \Json\Bench\Conf
     */
    protected $conf;

    /**
     * Constructor
     *
     * @param \Json\Bench\Conf $conf
     */
    public function __construct(Conf $conf) {
        $this->conf = $conf;
    }

    /**
     * Run benchmark
     * - walk output dir
     * - measure time json_decode and jsond_decode
     * - measure time json_encode and jsond_encode
     * - print results
     */
    public function run() {
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $loops = isset($sizeConf['loops']) ? $sizeConf['loops'] : 1;
            $this->runSize($output, $loops);
        }
    }

    /**
     * Run benchmark for size
     *
     * @param string $output
     * @param int    $loops
     */
    protected function runSize($path, $loops) {
        if (is_dir($path)) {
            foreach (new \DirectoryIterator($path) as $fileInfo) {
                if (!$fileInfo->isDot()) {
                    $fname = $fileInfo->getFilename();
                    $this->runSize("$path/$fname", $loops);
                }
            }
        } elseif ($this->conf->isWhiteListed($path, false)) {
            $this->bench($path, $loops);
        }
    }

    protected function bench($path, $loops) {
        printf("FILE: %s\n", $path);
        $string = file_get_contents($path);
        // Decoding
        $decodeTestResult = $this->testDecode($string);
        $jsonDecodeTime = $this->benchJsonDecode($string, $loops);
        $jsondDecodeTime = $this->benchJsondDecode($string, $loops);
        printf("DECODING [%s]: json: %s :: jsond: %s\n", $decodeTestResult, $jsonDecodeTime, $jsondDecodeTime);
        // Encoding
        $zval = json_decode($string);
        $encodeTestResult = $this->testEncode($string);
        $jsonEncodeTime = $this->benchJsonEncode($zval, $loops);
        $jsondEncodeTime = $this->benchJsondEncode($zval, $loops);
        printf("ENCODING [%s]: json: %s :: jsond: %s\n\n", $encodeTestResult, $jsonEncodeTime, $jsondEncodeTime);
    }

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

    protected function benchJsonDecode($string, $loops) {
        $start = microtime(true);
        for ($i = 0; $i < $loops; $i++) {
            json_decode($string);
        }
        $end = microtime(true);
        return $end - $start;
    }

    protected function benchJsondDecode($string, $loops) {
        $start = microtime(true);
        for ($i = 0; $i < $loops; $i++) {
            jsond_decode($string);
        }
        $end = microtime(true);
        return $end - $start;
    }

    protected function benchJsonEncode($zval, $loops) {
        $start = microtime(true);
        for ($i = 0; $i < $loops; $i++) {
            json_encode($zval);
        }
        $end = microtime(true);
        return $end - $start;
    }

    protected function benchJsondEncode($zval, $loops) {
        $start = microtime(true);
        for ($i = 0; $i < $loops; $i++) {
            jsond_encode($zval);
        }
        $end = microtime(true);
        return $end - $start;
    }
}
