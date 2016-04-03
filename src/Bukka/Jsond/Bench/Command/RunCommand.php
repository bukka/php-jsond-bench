<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Run command for running all benchmarks
 */
class RunCommand extends AbstractCommand
{
    /**
     * Whether the command has white list option
     *
     * @return bool
     */
    public function hasWhiteList()
    {
        return true;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Run benchmarks on the output instances')
            ->addOption(
                'save',
                's',
                InputOption::VALUE_NONE,
                'Whether the run results should be saved for the view'
            )
            ->addOption(
                'action',
                'a',
                InputOption::VALUE_REQUIRED,
                'The only action that will be run'
            )
        ;

        parent::configure();
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
        $this->conf->setParam('save', $input->getOption('save'));
        $this->conf->setRunAction($input->getOption('action'));
        // execute action
        parent::execute($input, $output);
    }
}