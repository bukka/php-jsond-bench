<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Action\GenerateAction;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Gen command
 */
class GenerateCommand extends Command
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
            ->setName('generate')
            ->setDescription('Generate the output instances')
            ->addArgument(
                'whiteList',
                InputArgument::IS_ARRAY,
                'White listed directories'
            )
            ->addOption(
               'force',
               'f',
               InputOption::VALUE_NONE,
               'Whether to force generation'
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
        if ($input->getOption('force')) {
            $this->conf->enableForce();
        }
        // create action
        $action = new GenerateAction($this->conf, $output);
        $action->execute();
    }
}