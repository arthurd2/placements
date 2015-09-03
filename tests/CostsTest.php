<?php


class CostsTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @expectedException Exception
     */
    public function testExceptionRulesFreeOfContext() {
        $handler = Costs::getInstance();
        $this->assertEquals('Costs', get_class($handler), "Class Does not match");
        $handler->add('xxx');
    }
    
    /**
     * @expectedException Exception
     */
    public function testExtendRule() {
        $handler = Costs::getInstance();
        $handler->add('COSText');
        $this->assertTrue(False,'Accept a non implemented InterfaceCost Class');
    }

    /**
     * @expectedException Exception
     */
    public function testImplementRule() {
        $handler = Costs::getInstance();
        $handler->add('COSTimp');
        $this->assertTrue(False,'Accept a non Cost extended Class');
    }

     public function testGetCostEmptyClasses() {
        $handler = Costs::getInstance();
        $x = 1;
        $this->assertequals(1,$handler->getCost($x), "Cost should return 1 without classes");
    }   
	/**
     * @depends testGetCostEmptyClasses
     */
    public function testCOSTextimp() {
        $handler = Costs::getInstance();
        $this->assertEquals('Costs', get_class($handler), "Class Should Be Accepted");
        $handler->add('COSTextimp');
    }
    /**
     * @depends testCOSTextimp
     */
    public function testGetCost() {
        $handler = Costs::getInstance();
        $x = 1;
        $this->assertEquals(2,$handler->getCost($x), "Rule should Allow");
    }   
}


class COSText extends Cost{}
class COSTimp implements InterfaceCost{
	static function getCost(& $cvmp){}

}
class COSTextimp extends Cost implements InterfaceCost{
	static function getCost(& $cvmp){ 
		return 2;
	}
}