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
            $this->benchFile($path, $loops);
        }
    }

    /**
     * Bench file instance
     *
     * @param string $path
     * @param int $loops
     */
    protected function benchFile($path, $loops) {
        printf("FILE: %s\n", $path);
        $string = file_get_contents($path);
        // Decoding
        $decodeTestResult = $this->testDecode($string);
        $jsonDecodeTime = $this->bench('json/decode', $path, $loops);
        $jsondDecodeTime = $this->bench('jsond/decode', $path, $loops);
        printf("DECODING [%s]: json: %s :: jsond: %s\n", $decodeTestResult, $jsonDecodeTime, $jsondDecodeTime);
        // Encoding
        $encodeTestResult = $this->testEncode($string);
        $jsonEncodeTime = $this->bench('json/encode', $path, $loops);
        $jsondEncodeTime = $this->bench('jsond/encode', $path, $loops);
        printf("ENCODING [%s]: json: %s :: jsond: %s\n\n", $encodeTestResult, $jsonEncodeTime, $jsondEncodeTime);
    }

    /**
     * Bench file
     *
     * @param string $name
     * @param string $path
     * @param int    $loops
     *
     * @return float
     */
    protected function bench($name, $path, $loops) {
        $bench = $this->conf->getBenchDir() . $name . '.php';
        $command = "php $bench $path $loops";
        $result = json_decode(exec($command));
        return isset($result->time) ? $result->time : 0;
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
