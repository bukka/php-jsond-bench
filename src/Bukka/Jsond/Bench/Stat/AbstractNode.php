<?php

namespace Bukka\Jsond\Bench\Stat;

use Bukka\Jsond\Bench\Writer\WriterInterface;

abstract class AbstractNode implements NodeInterface
{
    /**
     * Runs
     *
     * It's formatted as
     *
     * array(
     *      'json' => array($result1, $result2, $result2),
     *      'jsond' => array($result1, $result2, $result2)
     * )
     *
     * or if it's a leaf node (Item) then
     *
     * array(
     *      'json' => $resultValue1,
     *      'jsond' => $resultValue2,
     * )
     *
     * @var array
     */
    protected $runs;

    /**
     * Levels
     *
     * @var array
     */
    protected $levels;


    /**
     * Get levels
     *
     * @return mixed
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * Get levels
     *
     * @param mixed $defaultValue
     *
     * @return mixed
     */
    public function getCurrentLevel($defaultValue = null)
    {
        return empty($this->levels) ? $defaultValue : $this->levels[0];
    }

    /**
     * Get children
     *
     * @return array
     */
    protected function getChildren()
    {
        $children = array();
        foreach ($this->runs as $results) {
            if (is_array($results)) {
                foreach ($results as $result) {
                    $children[spl_object_hash($result)] = $result;
                }
            }
        }
        return $children;
    }

    /**
     * Dump node and its children
     *
     * @param WriterInterface $writer
     */
    public function dump(WriterInterface $writer)
    {
        $writer->formatLine("Level: %s", $this->getCurrentLevel('top'));
        $writer->incLevel();
        $avgResults = $this->getAvgRunTime();
        foreach ($avgResults as $runName => $avgResult) {
            $writer->formatLine("%-6s: %f", $runName, $avgResult);
        }
        $writer->writeLine();
        foreach ($this->getChildren() as $child) {
            $writer->incLevel();
            $child->dump($writer);
            $writer->decLevel();
        }
        $writer->decLevel();
    }
}