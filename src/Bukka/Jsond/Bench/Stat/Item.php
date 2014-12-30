<?php

namespace Bukka\Jsond\Bench\Stat;

class Item implements NodeInterface
{
    /**
     * @var array
     */
    protected $runs = array();

    /**
     * @var integer
     */
    protected $loops = 1;

    /**
     * Constructor
     *
     * @param mixed $storageRecord
     * @param array $runAliases
     */
    public function __construct($storageRecord = null, array $runAliases = array())
    {
        if ($storageRecord) {
            $this->addRuns($storageRecord->runs, $runAliases);
            $this->setLoops($storageRecord->loops);
        }
    }

    /**
     * @param $loops
     */
    public function setLoops($loops)
    {
        $this->loops = $loops;
    }

    /**
     * @param string $name
     * @param string $value
     * @param array  $runAliases
     */
    public function addRun($name, $value, array $runAliases = array())
    {
        if (isset($runAliases[$name])) {
            $name = $runAliases[$name];
        }
        $this->runs[$name] = $value;
    }

    /**
     * @param $runs
     * @param array $runAliases
     */
    public function addRuns($runs, array $runAliases = array())
    {
        foreach ($runs as $name => $value) {
            $this->addRun($name, $value, $runAliases);
        }
    }

    /**
     * Get total running time
     *
     * @param string $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getTotalRunTime($name = null)
    {
        if (is_null($name)) {
            return $this->runs;
        }
        if (isset($this->runs[$name])) {
            return $this->runs[$name];
        }
        throw new \InvalidArgumentException("No run called $name");
    }

    /**
     * Get average running time
     *
     * @param string $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getAvgRunTime($name = null)
    {
        return $this->getTotalRunTime($name);
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
        if (is_null($name)) {
            return array_fill_keys(array_keys($this->runs), 1);
        }
        if (isset($this->runs[$name])) {
            return 1;
        }
        throw new \InvalidArgumentException("No run called $name");
    }

    /**
     * Get number of loops
     *
     * @return integer
     */
    public function getLoops()
    {
        return $this->loops;
    }
}