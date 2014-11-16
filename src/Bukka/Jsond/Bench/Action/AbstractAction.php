<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;

/**
 * Templates checking class
 */
abstract class AbstractAction
{
     /**
     * Main configuration
     *
     * @var \Json\Bench\Conf\Conf
     */
    protected $conf;

    /**
     * Constructor
     *
     * @param \Json\Bench\Conf\Conf $conf
     */
    public function __construct(Conf $conf) {
        $this->conf = $conf;
    }

    abstract function execute();
}
