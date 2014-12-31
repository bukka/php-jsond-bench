<?php

namespace Bukka\Jsond\Bench\Stat;

class Node implements NodeInterface
{
    /**
     * It's formatted as
     * array(
     *      'json' => array($result1, $result2, $result2),
     *      'jsond' => array($result1, $result2, $result2)
     * )
     *
     * @var array
     */
    protected $runs;

    /**
     * Cached total number of loops
     *
     * @var array
     */
    protected $cache = array();

    /**
     * Get cached run value
     *
     * @param mixed   $name
     * @param string  $type
     * @param boolean $average
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    protected function getCachedRunValue($name, $type, $average = false)
    {
        if (!isset($this->cache[$type])) {
            $this->cache[$type] = array();
            foreach ($this->runs as $runName => $results) {
                $values = array_map(
                    function($result) use ($type, $runName) { $result->{'get' . $type}($runName); },
                    $results
                );
                $valuesSum = array_sum($values);
                $this->cache[$type][$runName] = $average ? $valuesSum / count($values) : $valuesSum;
            }
        }
        if (is_null($name)) {
            return $this->cache[$type];
        }
        if (isset($this->cache[$type][$name])) {
            return $this->cache[$type][$name];
        }
        throw new \InvalidArgumentException("No run called $name");
    }

    /**
     * Get total running time
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getTotalRunTime($name = null)
    {
        return $this->getCachedRunValue($name, 'TotalRunTime');
    }

    /**
     * Get average running time
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getAvgRunTime($name = null)
    {
        return $this->getCachedRunValue($name, 'AvgRunTime', true);
    }

    /**
     * Get run count
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getRunCount($name = null)
    {
        return $this->getCachedRunValue($name, 'RunCount');
    }

    /**
     * Get number of loops
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getLoops($name = null)
    {
        return $this->getCachedRunValue($name, 'Loops');
    }
}