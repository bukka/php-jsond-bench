<?php

namespace Bukka\Jsond\Bench\Writer;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Console writer
 */
class ConsoleWriter extends AbstractWriter
{
    /**
     * Console output
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Indentation
     *
     * @var string
     */
    protected $indent = '';

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
     * @param string $message
     *
     * @return null
     */
    public function write($message = '')
    {
        $this->output->write($this->indent . $message);
    }

    /**
     * Write message and NL
     *
     * @param string $message
     *
     * @return null
     */
    public function writeLine($message = '')
    {
        $this->output->writeln($this->indent . $message);
    }

    /**
     * Set level and indent
     *
     * @param integer $level
     *
     * @return null
     */
    public function setLevel($level)
    {
        parent::setLevel($level);
        $this->indent = str_repeat(' ', $level);
    }
}