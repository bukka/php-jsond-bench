<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Templates checking class
 */
abstract class AbstractAction
{
     /**
     * Main configuration
     *
     * @var Conf
     */
    protected $conf;

    /**
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Constructor
     *
     * @param Conf            $conf
     * @param OutputInterface $output
     */
    public function __construct(Conf $conf, OutputInterface $output)
    {
        $this->conf = $conf;
        $this->output = $output;
    }

    /**
     * Write a message to the output
     *
     * @param string|array $messages The message as an array of lines or a single string
     */
    protected function write($messages)
    {
        $this->output->write($messages);
    }

    /**
     * Write a message to the output and add a newline at the end
     *
     * @param string|array $messages The message as an array of lines or a single string
     */
    protected function writeln($messages)
    {
        $this->output->writeln($messages);
    }

    /**
     * Printf to the output
     */
    protected function printf()
    {
        $this->write(call_user_func_array("sprintf", func_get_args()));
    }

    /**
     * Abstract execution method
     */
    abstract function execute();
}
