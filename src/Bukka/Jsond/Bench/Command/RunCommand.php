<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run command for running all benchmarks
 */
class RunCommand extends AbstractCommand
{
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
            ->addOption(
                'save',
                's',
                InputOption::VALUE_NONE,
                'Whether the run results should be saved for the view'
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
        $this->conf->setWhiteList($input->getArgument('whiteList'));
        $this->conf->setParam('save', $input->getOption('save'));
        // execute action
        parent::execute($input, $output);
    }
}