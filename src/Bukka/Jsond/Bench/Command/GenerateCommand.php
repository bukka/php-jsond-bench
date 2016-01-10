<?php

namespace Bukka\Jsond\Bench\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Generate command for generating output files of templates
 */
class GenerateCommand extends AbstractCommand
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
            ->setName('generate')
            ->setDescription('Generate the output instances')
            ->addOption(
               'force',
               'f',
               InputOption::VALUE_NONE,
               'Whether to force generation'
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
        if ($input->getOption('force')) {
            $this->conf->enableForce();
        }
        // execute action
        parent::execute($input, $output);
    }
}