<?php

namespace Bukka\Jsond\Bench\Stat;

interface NodeInterface
{
    /**
     * Get names of all runs
     *
     * @return array
     */
    public function getRunNames();

    /**
     * Get total running time
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getTotalRunTime($name = null);

    /**
     * Get average running time
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getAvgRunTime($name = null);

    /**
     * Get run count
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getRunCount($name = null);

    /**
     * Get number of loops
     *
     * @param mixed $name Run name
     *
     * @return mixed
     *
     * @throws \InvalidArgumentException
     */
    public function getLoops($name = null);
}