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
            if (isset($this->data[$record->action][$c->size][$c->type][$c->org][$c->idx])) {
                $this->data[$record->action][$c->size][$c->type][$c->org][$c->idx]->addRuns($record->runs, $aliases);
            } else {
                $this->data[$record->action][$c->size][$c->type][$c->org][$c->idx] = new Item($record, $aliases);
            }
        }
    }

    /**
     * Process data
     */
    protected function processData()
    {
        foreach ($this->data as $actionName => $sizes) {
            $actionNode = new Node();
            foreach ($sizes as $sizeName => $types) {
                $sizeNode = new Node();
                foreach ($types as $typeName => $organizations) {
                    $typeNode = new Node();
                    foreach ($organizations as $organizationName => $indexes) {
                        $organizationNode = new Node();
                        foreach ($indexes as $indexName => $item) {
                            $organizationNode->addChild($item);
                        }
                        $typeNode->addChild($organizationNode);
                    }
                    $sizeNode->addChild($typeNode);
                }
                $actionNode->addChild($sizeNode);
            }
            $this->results[$actionName] = $actionNode;
            echo strtoupper($actionName) . "\n";
            print_r($actionNode->getAvgRunTime());
        }
    }
}
