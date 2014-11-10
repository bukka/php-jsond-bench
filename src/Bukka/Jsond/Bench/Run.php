<?php

namespace Bukka\Jsond\Bench;

/**
 * Benchmark run class
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
     * Storage
     *
     * @var\Bukka\Jsond\Bench\Storage\StorageInterface
     */
    protected $storage;

    /**
     * Constructor
     *
     * @param \Json\Bench\Conf $conf
     */
    public function __construct(Conf $conf) {
        $this->conf = $conf;
        $this->storage = $conf->getStorage();
    }

    /**
     * Run benchmark
     * - walk output dir
     * - measure time json_decode and jsond_decode
     * - measure time json_encode and jsond_encode
     * - print results
     */
    public function run() {
        $this->storage->open();
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $loops = isset($sizeConf['loops']) ? $sizeConf['loops'] : 1;
            $this->runSize($output, $loops);
        }
        $this->storage->close();
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
        // Decoding
        $jsonDecodeTime = $this->bench('json/decode', $path, $loops);
        $jsondDecodeTime = $this->bench('jsond/decode', $path, $loops);
        printf("DECODING json: %s :: jsond: %s\n",
                $jsonDecodeTime, $jsondDecodeTime);
        $this->storage->save($path, 'decode', $loops, array(
            'json' => $jsonDecodeTime,
            'jsond' => $jsondDecodeTime
        ));
        // Encoding
        $jsonEncodeTime = $this->bench('json/encode', $path, $loops);
        $jsondEncodeTime = $this->bench('jsond/encode', $path, $loops);
        printf("ENCODING: json: %s :: jsond: %s\n\n",
                $jsonEncodeTime, $jsondEncodeTime);
        $this->storage->save($path, 'encode', $loops, array(
            'json' => $jsonEncodeTime,
            'jsond' => $jsondEncodeTime
        ));
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
}
