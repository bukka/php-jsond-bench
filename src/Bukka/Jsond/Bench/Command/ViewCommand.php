<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Action\ViewAction;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'result date'
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
        
        // create action
        $action = new ViewAction($this->conf, $output);
        $action->execute();
    }
}