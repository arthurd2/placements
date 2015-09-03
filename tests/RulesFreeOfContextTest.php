<?php


class RulesFreeOfContextTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @expectedException Exception
     */
    public function testExceptionRulesFreeOfContext() {
        $handler = RulesFreeOfContext::getInstance();
        $this->assertEquals('RulesFreeOfContext', get_class($handler), "Class Does not match");
        $handler->add('xxx');
    }
    
    /**
     * @expectedException Exception
     */
    public function testExtendRule() {
        $handler = RulesFreeOfContext::getInstance();
        $handler->add('RFCextendRule');
        $this->assertTrue(False,'Accpet an non extended Rule Class');
    }

    /**
     * @expectedException Exception
     */
    public function testImplementRule() {
        $handler = RulesFreeOfContext::getInstance();
        $handler->add('RFCimplementRule');
        $this->assertTrue(False,'Accpet an non implemented RuleFreeOfContext Class');
    }

     public function testIsAllowedEmptyClasses() {
        $handler = RulesFreeOfContext::getInstance();
        $this->assertTrue($handler->isAllowed(1,2), "Class Does not match");
    }   
	/**
     * @depends testIsAllowedEmptyClasses
     */
    public function testExtImpRules() {
        $handler = RulesFreeOfContext::getInstance();
        $this->assertEquals('RulesFreeOfContext', get_class($handler), "Class Does not match");
        $handler->add('RFCextImpRules');
    }
    /**
     * @depends testExtImpRules
     */
    public function testIsAllowed() {
        $handler = RulesFreeOfContext::getInstance();
        $this->assertTrue($handler->isAllowed(1,2), "Class Does not match");
        $this->assertFalse($handler->isAllowed(2,2), "Class Does not match");
    }   
}


class RFCextendRule extends Rule{}
class RFCimplementRule implements RuleFreeOfContext{
	static function isAllowed(& $vm , & $pm){}
    static function isEnable(){}
    static function enable(){}
    static function disable(){}
}
class RFCextImpRules extends Rule implements RuleFreeOfContext{
	static function isAllowed(& $vm , & $pm){ 
		return ($vm === 1);
	}
}