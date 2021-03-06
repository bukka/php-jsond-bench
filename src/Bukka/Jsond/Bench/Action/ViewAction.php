<?php

namespace Bukka\Jsond\Bench\Action;

use Bukka\Jsond\Bench\Exception\ActionException;
use Bukka\Jsond\Bench\Stat\Item;
use Bukka\Jsond\Bench\Stat\Node;

/**
 * View action
 */
class ViewAction extends AbstractAction
{
    /**
     * Processed data
     *
     * @var array
     */
    protected $data;

    /**
     * Results for encoding and decoding
     *
     * @var array
     */
    protected $results;

    /**
     * Execute viewing of results
     */
    public function execute() {
        $this->loadDates();
        $this->processData();
        $this->dumpResults();
    }

    /**
     * Load all dates
     */
    protected function loadDates()
    {
        $this->data = array();
        $dates = $this->conf->getParam('dates');
        if (empty($dates)) {
            // load last date
            $this->loadDate();
        } else {
            foreach ($dates as $date) {
                $this->loadDate($date);
            }
        }
    }

    /**
     * Load single date
     *
     * @param mixed $date
     *
     * @throws ActionException
     */
    protected function loadDate($date = null)
    {
        $elements = explode(':', $date);
        $fileData = $this->getData($elements[0]);
        if (is_null($fileData)) {
            throw new ActionException('No data for ' . $elements[0]);
        }
        $aliases = array();
        if (count($elements) > 1) {
            foreach (explode(';', $elements[1]) as $alias) {
                if (strpos($alias, '=') !== false) {
                    list($aliasTarget, $aliasSource) = explode('=', $alias, 2);
                    $aliases[$aliasSource] = $aliasTarget;
                }
            }
        }
        $this->setData($fileData, $aliases);
    }

    /**
     * Get data for dateTime
     *
     * @param string $dateTime
     *
     * @return array
     */
    protected function getData($dateTime)
    {
        return $this->conf->getStorage()->load($dateTime);
    }

    /**
     * Save locally data
     *
     * @param array $fileData
     * @param array $aliases
     */
    protected function setData($fileData, $aliases)
    {
        foreach ($fileData as $record) {
            $c = $record->category;
            if (isset($this->data[$record->action][$c->size][$c->type][$c->org][$c->name][$c->idx])) {
                $this->data[$record->action][$c->size][$c->type][$c->org][$c->name][$c->idx]->addRuns($record->runs, $aliases);
            } else {
                $this->data[$record->action][$c->size][$c->type][$c->org][$c->name][$c->idx] = new Item($record, $aliases, $c);
            }
        }
    }

    /**
     * Process data nodes
     *
     * @param array $nodes
     *
     * @return Node
     */
    protected function processDataNodes(array $nodes)
    {
        $node = new Node();
        foreach ($nodes as $name => $child) {
            if (is_array($child)) {
                $node->addChild($this->processDataNodes($child));
            } else {
                $node->addChild($child);
            }
        }

        return $node;
    }

    /**
     * Process data
     */
    protected function processData()
    {
        foreach ($this->data as $actionName => $sizes) {
            $this->results[$actionName] = $this->processDataNodes($sizes);
        }
    }

    /**
     * Dump result data
     */
    protected function dumpResults()
    {
        foreach ($this->results as $actionName => $actionNode) {
            $this->writeln(strtoupper($actionName));
            $actionNode->dump($this->writer);
        }
    }
}
