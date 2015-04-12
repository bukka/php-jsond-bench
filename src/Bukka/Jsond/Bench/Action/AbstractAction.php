<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Conf\Conf;
use Bukka\Jsond\Bench\Writer\WriterInterface;

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
     * Writer
     *
     * @var WriterInterface
     */
    protected $writer;

    /**
     * Constructor
     *
     * @param Conf            $conf
     * @param WriterInterface $writer
     */
    public function __construct(Conf $conf, WriterInterface $writer)
    {
        $this->conf = $conf;
        $this->writer = $writer;
    }

    /**
     * Write a message to the output
     *
     * @param string|array $messages The message as an array of lines or a single string
     */
    protected function write($messages = '')
    {
        $this->writer->write($messages);
    }

    /**
     * Write a message to the output and add a newline at the end
     *
     * @param string|array $messages The message as an array of lines or a single string
     */
    protected function writeln($messages = '')
    {
        $this->writer->writeLine($messages);
    }

    /**
     * Printf to the output
     */
    protected function printf()
    {
        $this->writer->format(call_user_func_array("sprintf", func_get_args()));
    }

    /**
     * Abstract execution method
     */
    abstract function execute();
}
