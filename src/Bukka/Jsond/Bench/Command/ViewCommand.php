<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Action\ViewAction;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * View command for viewing results of benchmarks
 */
class ViewCommand extends Command
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
            ->setName('view')
            ->setDescription('View results')
            ->addOption(
                'level',
                'l',
                InputOption::VALUE_REQUIRED,
                'level of displayed results (default to all levels)'
            )
            ->addArgument(
                'dates',
                InputArgument::IS_ARRAY,
                'result dates with possible aliases (e.g. 2014-12-12:json_new=json)'
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
        // set conf
        $this->conf->setParam('dates', $input->getArgument('dates'));
        if ($input->hasOption('level')) {
            $this->conf->setParam('level', $input->getOption('level'));
        }
        // create action
        $action = new ViewAction($this->conf, $output);
        $action->execute();
    }
}