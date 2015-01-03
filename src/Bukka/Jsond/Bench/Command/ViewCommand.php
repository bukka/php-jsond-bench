<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * View command for viewing results of benchmarks
 */
class ViewCommand extends AbstractCommand
{
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
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // set conf
        $this->conf->setParam('dates', $input->getArgument('dates'));
        if ($input->hasOption('level')) {
            $this->conf->setParam('level', $input->getOption('level'));
        }
        // execute action
        parent::execute($input, $output);
    }
}