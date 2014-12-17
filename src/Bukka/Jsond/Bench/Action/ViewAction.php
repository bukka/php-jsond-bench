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

    }
}
