<?php

namespace Bukka\Jsond\Bench\Stat;

class Item
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


}