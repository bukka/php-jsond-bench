<?php

namespace Bukka\Jsond\Bench\Tests\Stat;

use Bukka\Jsond\Bench\Stat\Item;

class ItemTest extends \PHPUnit_Framework_TestCase
{
    public function testSetLevelsFromCategory()
    {
        $item = new Item();

        $category = (object) array(
            'idx'  => 1,
            'name' => 'string_basic',
            'org'  => 'array',
            'type' => 'float',
            'size' => 'tiny',
        );
        $item->setLevelsFromCategory($category);

        $expectedArray = array(1, 'string_basic', 'array', 'float', 'tiny');

        $this->assertEquals($expectedArray, $item->getLevels());
    }

    public function testAddRun()
    {
        $item = new Item();
        $item->addRun("json", 1.344343);

        $runs = $item->getRuns();
        $this->assertArrayHasKey("json", $runs);
        $this->assertEquals(1.344343, $runs['json'], "Invalid value for json run", 0.0000001);

        $item->addRun("json", 1.111111, array("json" => "json2"));
        $runs = $item->getRuns();
        $this->assertCount(2, $runs);
        $this->assertArrayHasKey("json", $runs);
        $this->assertArrayHasKey("json2", $runs);
        $this->assertEquals(1.111111, $runs['json2'], "Invalid value for json2 run", 0.0000001);
    }

    public function testAddRuns()
    {
        $runs = array(
            "json" => 1.222222,
            "jsond" => 1.111111,
        );

        $item = new Item();
        $item->addRuns($runs);
        $runs = $item->getRuns();

        $this->assertCount(2, $runs);
        $this->assertArrayHasKey("json", $runs);
        $this->assertArrayHasKey("jsond", $runs);
        $this->assertEquals(1.111111, $runs['jsond'], "Invalid value for jsond run", 0.0000001);
        $this->assertEquals(1.222222, $runs['json'], "Invalid value for json run", 0.0000001);

        $item = new Item();
        $item->addRuns($runs, array('json' => 'jsonx'));
        $runs = $item->getRuns();
        $this->assertArrayHasKey("jsonx", $runs);
        $this->assertArrayHasKey("jsond", $runs);
        $this->assertEquals(1.111111, $runs['jsond'], "Invalid value for jsond run", 0.0000001);
        $this->assertEquals(1.222222, $runs['jsonx'], "Invalid value for jsonx run", 0.0000001);
    }

    /**
     * @depends testAddRuns
     */
    public function testGetRunNames()
    {
        $runs = array(
            "json" => 1.222222,
            "jsond" => 1.111111,
        );

        $item = new Item();
        $item->addRuns($runs);

        $this->assertEquals(array('json', 'jsond'), $item->getRunNames());
    }

    public function testLoops()
    {
        $item = new Item();

    }
}