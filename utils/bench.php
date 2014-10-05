<?php

namespace Json\Bench;

use Json\Bench\Conf;

/**
 * Benchmark class
 */
class Bench
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
        
    }
}
