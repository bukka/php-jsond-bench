<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Writer\ConsoleWriter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * Main configuration
     *
     * @var Conf
     */
    protected $conf;

    /**
     * Constructor
     *
     * @param Conf  $conf
     * @param mixed $name
     */
    public function __construct(Conf $conf, $name = null)
    {
        parent::__construct($name);
        $this->conf = $conf;
    }

    /**
     * Execute action
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actionClass = '\Bukka\Jsond\Bench\Action\\' . ucfirst($this->getName()) . 'Action';
        if (!class_exists($actionClass)) {
            throw new \LogicException('No action class for ' . $this->getName());
        }
        $writer = new ConsoleWriter($output);
        $action = new $actionClass($this->conf, $writer);
        $action->execute();
    }
}