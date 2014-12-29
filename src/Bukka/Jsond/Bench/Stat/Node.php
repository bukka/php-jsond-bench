<?php

namespace Bukka\Jsond\Bench\Stat;

class Node implements NodeInterface
{
    /**
     * It's formatted as
     * array(
     *      'json' => array($result1, $result2, $result2)
     *
     * @var array
     */
    protected $runs;

    /**
     * Cached total number of loops
     *
     * @var integer
     */
    protected $loops;

    /**
     * cached total run time
     *
     * @var array
     */
    protected $totalRunTime;

    /**
     * cached total avg time
     *
     * @var array
     */
    protected $totalAvgTime;

    /**
     * cached runs count
     *
     * @var array
     */
    protected $runsCount;

    /**
     * Get total running time
     *
     * @param mixed $name Run name
     *
     * @return float
     *
     * @throws \InvalidArgumentException
     */
    public function getTotalRunTime($name = null)
    {
        // TODO: Implement getTotalRunTime() method.
    }

    /**
     * Get average running time
     *
     * @param mixed $name Run name
     *
     * @return float
     *
     * @throws \InvalidArgumentException
     */
    public function getAvgRunTime($name = null)
    {
        // TODO: Implement getAvgRunTime() method.
    }

    /**
     * Get run count
     *
     * @param mixed $name Run name
     *
     * @return integer
     *
     * @throws \InvalidArgumentException
     */
    public function getRunCount($name = null)
    {
        // TODO: Implement getRunCount() method.
    }

    /**
     * Get number of loops
     *
     * @return integer
     */
    public function getLoops()
    {
        // TODO: Implement getLoops() method.
    }


}