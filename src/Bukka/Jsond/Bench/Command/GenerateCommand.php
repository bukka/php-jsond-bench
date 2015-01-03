<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Gen command
 */
class GenerateCommand extends AbstractCommand
{
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
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->conf->setWhiteList($input->getArgument('whiteList'));
        if ($input->getOption('force')) {
            $this->conf->enableForce();
        }
        // execute action
        parent::execute($input, $output);
    }
}