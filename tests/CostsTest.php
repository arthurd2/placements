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
        Costs::add('COSText');
        $this->assertTrue(False,'Accept a non implemented InterfaceCost Class');
    }

    /**
     * @expectedException Exception
     */
    public function testImplementRule() {
        Costs::add('COSTimp');
        $this->assertTrue(False,'Accept a non Cost extended Class');
    }

     public function testGetCostEmptyClasses() {
        $x = 1;
        $this->assertequals(1,Costs::getCost($x), "Cost should return 1 without classes");
    }   
	/**
     * @depends testGetCostEmptyClasses
     */
    public function testCOSTextimp() {
        Costs::add('COSTextimp');
        $this->assertTrue(True, "Should accept this class");
    }
    /**
     * @depends testCOSTextimp
     */
    public function testGetCost() {
        $x = 1;
        $this->assertEquals(2,Costs::getCost($x), "");
    }

    //TODO testar del de Costs, ver se substitui mesmo.
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