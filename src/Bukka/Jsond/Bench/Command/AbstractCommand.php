<?php

namespace Bukka\Jsond\Bench\Command;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Writer\ConsoleWriter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
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
     * @param Conf  $conf
     * @param mixed $name
     */
    public function __construct(Conf $conf, $name = null)
    {
        parent::__construct($name);
        $this->conf = $conf;
    }

    /**
     * Whether the command has white list option
     *
     * @return bool
     */
    public function hasWhiteList()
    {
        return false;
    }

    /**
     * Configure command
     */
    protected function configure()
    {
        if ($this->hasWhiteList()) {
            $this->addArgument(
                'whiteListDirs',
                InputArgument::IS_ARRAY,
                'White listed directories'
            );
            $this->addOption(
                'whiteList',
                'w',
                InputOption::VALUE_REQUIRED,
                'Predefined White list'
            );
        }
    }

    /**
     * Execute action
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actionClass = '\Bukka\Jsond\Bench\Action\\' . ucfirst($this->getName()) . 'Action';
        if (!class_exists($actionClass)) {
            throw new \LogicException('No action class for ' . $this->getName());
        }

        if ($this->hasWhiteList()) {
            $whiteList = $input->hasOption('whiteList') && $input->getOption('whiteList') ?
                $input->getOption('whiteList') :
                $input->getArgument('whiteListDirs');
            $this->conf->setWhiteList($whiteList);
        }

        $writer = new ConsoleWriter($output);
        $action = new $actionClass($this->conf, $writer);
        $action->execute();
    }
}