<?php

namespace Bukka\Jsond\Bench\Stat;

/**
 * Item node
 */
class Item extends AbstractNode
{
    /**
     * @var integer
     */
    protected $loops = 1;

    /**
     * Constructor
     *
     * @param mixed $storageRecord
     * @param array $runAliases
     * @param mixed $category
     */
    public function __construct($storageRecord = null, array $runAliases = array(), $category = null)
    {
        if ($storageRecord) {
            $this->addRuns($storageRecord->runs, $runAliases);
            $this->setLoops($storageRecord->loops);
        }
        if ($category) {
            $this->setLevelsFromCategory($category);
        }
    }

    /**
     * Set levels from category
     *
     * @param object $category
     */
    public function setLevelsFromCategory($category)
    {
        $this->levels = array(
            $category->idx,
            $category->name,
            $category->org,
            $category->type,
            $category->size,
        );
    }

    /**
     * Set loops
     *
     * @param integer $loops
     */
    public function setLoops($loops)
    {
        $this->loops = $loops;
    }

    /**
     * Add a single run
     *
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
     * Add multiple runs
     *
     * @param array $runs
     * @param array $runAliases
     */
    public function addRuns($runs, array $runAliases = array())
    {
        foreach ($runs as $name => $value) {
            $this->addRun($name, $value, $runAliases);
        }
    }

    /**
     * Get names of all runs
     *
     * @return array
     */
    public function getRunNames()
    {
        return array_keys($this->runs);
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
            return array_fill_keys($this->getRunNames($this->runs), 1);
        }
        if (isset($this->runs[$name])) {
            return 1;
        }
        throw new \InvalidArgumentException("No run called $name");
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
        if (is_null($name)) {
            return array_fill_keys($this->getRunNames($this->runs), $this->loops);
        }
        if (isset($this->runs[$name])) {
            return $this->loops;
        }
    }
}