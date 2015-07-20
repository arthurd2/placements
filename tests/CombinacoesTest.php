<?php
require_once "src/model/Combinations.php";

class CombinationsTest extends PHPUnit_Framework_TestCase
{
    public function testAllCombinationsAndFilter() {
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $combinations = Combinations::GenerateAllCombinations($scenario['placements']);
        $this->assertEquals(24,count($combinations));

        $filtered = Combinations::FilterCombinationsByMaxVm($combinations,2);
        $this->assertEquals(16,count($filtered));
    }

    public function testWillOverload() {
        $place1 = 'D:2';
        $place2 = 'D:1';
        $max = 3;
        $placements = array('A:2','B:2','C:2');
        $resp = Combinations::WillOverload($place1,$placements,$max);
        $this->assertTrue($resp);
        $resp = Combinations::WillOverload($place2,$placements,$max);
        $this->assertFalse($resp);
    }

    /**
     * @depends testWillOverload
     */
    public function testGenerateAllCombinationsMaxVM() {
        $scenario['placements'] = array(
            'a' => array('A:2', 'A:3'), 
            'b' => array('B:1', 'B:2'), 
            'c' => array('C:2', 'C:3'), 
            'd' => array('D:1', 'D:2', 'D:3'));
        $filtered = Combinations::GenerateAllCombinationsMaxVM($scenario['placements'],2);
        $this->assertEquals(16,count($filtered));
        $filtered = Combinations::GenerateAllCombinationsMaxVM($scenario['placements'],3);
        $this->assertEquals(23,count($filtered));
    }

    
}
