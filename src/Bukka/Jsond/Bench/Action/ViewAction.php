<?php

namespace Bukka\Jsond\Bench\Action;

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
     * Execute viewing of results
     */
    public function execute() {
        $this->loadDates();
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
     * @param string $date
     */
    protected function loadDate($date = null)
    {
        $elements = explode(':', $date);
        $fileData = $this->getData($elements[0]);
        if (is_null($fileData)) {
            throw new \Exception('No data for ' . $elements[0]);
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
        $this->saveData($fileData, $aliases);
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
     * Save localy data
     *
     * @param array $fileData
     * @param array $aliases
     */
    protected function saveData($fileData, $aliases)
    {
        foreach ($fileData as $record) {
            $c = $record->category;
            // $this->data[$c->size][$c->type][$c->org][$cat->idx];
        }
    }
}
