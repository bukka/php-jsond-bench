<?php

namespace Bukka\Jsond\Bench\Writer;

use Symfony\Component\Console\Output\OutputInterface;

class ConsoleWriter extends AbstractWriter
{
    /**
     * Console output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * Write message
     *
     * @param $message
     *
     * @return null
     */
    public function write($message)
    {
        $this->output->write($message);
    }

    /**
     * Write message and NL
     *
     * @param $message
     *
     * @return null
     */
    public function writeLine($message)
    {
        $this->output->writeln($message);
    }
}