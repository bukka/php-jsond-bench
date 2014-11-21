<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Action\RunAction;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run command for running all benchmarks
 */
class RunCommand extends Command
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
     * @param Conf $conf
     */
    public function __construct(Conf $conf, $name = null)
    {
        parent::__construct($name);
        $this->conf = $conf;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run benchmarks on the output instances')
            ->addArgument(
                'whiteList',
                InputArgument::IS_ARRAY,
                'White listed directories'
            )
        ;
    }

    /**
     * Execute command
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->conf->setWhiteList($input->getArgument('whiteList'));
        // create action
        $action = new RunAction($this->conf);
        $action->execute();
    }
}