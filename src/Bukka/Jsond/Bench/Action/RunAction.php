<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Util\DirectorySortedIterator;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Benchmark run class
 */
class RunAction extends AbstractAction
{
    /**
     * Storage
     *
     * @var\Bukka\Jsond\Bench\Storage\StorageInterface
     */
    protected $storage;

    /**
     * Constructor
     *
     * @param Conf            $conf
     * @param OutputInterface $output
     */
    public function __construct(Conf $conf, OutputInterface $output) {
        parent::__construct($conf, $output);
        $this->storage = $conf->getStorage();
    }

    /**
     * Run benchmark
     * - walk output dir
     * - measure time json_decode and jsond_decode
     * - measure time json_encode and jsond_encode
     * - print results
     */
    public function execute() {
        $this->storage->open();
        foreach ($this->conf->getSizes() as $sizeName => $sizeConf) {
            $output = $this->conf->getOutputDir() . $sizeName;
            $loops = isset($sizeConf['loops']) ? $sizeConf['loops'] : 1;
            $this->executeSize($output, $loops);
        }
        $this->storage->close();
    }

    /**
     * Run benchmark for size
     *
     * @param string $output
     * @param int    $loops
     */
    protected function executeSize($path, $loops) {
        if (is_dir($path)) {
            foreach (new DirectorySortedIterator($path) as $fileInfo) {
                $fname = $fileInfo->getFilename();
                $this->executeSize("$path/$fname", $loops);
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
        $this->printf("FILE: %s\n", $path);
        // Decoding
        $jsonDecodeTime = $this->bench('json/decode', $path, $loops);
        $jsondDecodeTime = $this->bench('jsond/decode', $path, $loops);
        $this->printf("DECODING json: %s :: jsond: %s\n",
                $jsonDecodeTime, $jsondDecodeTime);
        $this->storage->save($path, 'decode', $loops, array(
            'json' => $jsonDecodeTime,
            'jsond' => $jsondDecodeTime
        ));
        // Encoding
        $jsonEncodeTime = $this->bench('json/encode', $path, $loops);
        $jsondEncodeTime = $this->bench('jsond/encode', $path, $loops);
        $this->printf("ENCODING: json: %s :: jsond: %s\n\n",
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
