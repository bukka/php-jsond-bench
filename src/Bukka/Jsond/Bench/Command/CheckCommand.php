<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Check command
 */
class CheckCommand extends AbstractCommand
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
            ->setName('check')
            ->setDescription('Check the output instances')
            ->addOption(
                'all',
                'a',
                InputOption::VALUE_NONE,
                'Whether to show successful checks'
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
        $this->conf->setParam('all', $input->getOption('all'));
        // execute action
        parent::execute($input, $output);
    }
}