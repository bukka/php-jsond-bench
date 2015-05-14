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

        $this->assertEquals(array(
            1, 'string_basic', 'array', 'float', 'tiny'
        ), $item->getLevels());
    }
}