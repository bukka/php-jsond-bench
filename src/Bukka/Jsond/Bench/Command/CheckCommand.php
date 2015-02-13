<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Bench command
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
        // execute action
        parent::execute($input, $output);
    }
}