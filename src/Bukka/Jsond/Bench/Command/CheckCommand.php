<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputInterface;
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
        ;

        parent::configure();
    }
}