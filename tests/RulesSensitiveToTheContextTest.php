<?php


class RulesSensitiveToTheContextTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @expectedException Exception
     */
    public function testExceptionRulesFreeOfContext() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $this->assertEquals('RulesSensitiveToTheContext', get_class($handler), "Class Does not match");
        $handler->add('xxx');
    }
    
    /**
     * @expectedException Exception
     */
    public function testExtendRule() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $handler->add('RSCextendRule');
        $this->assertTrue(False,'Accept a non extended Rule Class');
    }

    /**
     * @expectedException Exception
     */
    public function testImplementRule() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $handler->add('RSCimplementRule');
        $this->assertTrue(False,'Accept a non implemented RuleSensitiveToTheContext Class');
    }

     public function testIsAllowedEmptyClasses() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $this->assertTrue($handler->isAllowed(1), "Rule should Allow without classes");
    }   
	/**
     * @depends testIsAllowedEmptyClasses
     */
    public function testExtImpRules() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $this->assertEquals('RulesSensitiveToTheContext', get_class($handler), "Class Should Be Accepted");
        $handler->add('RSCextImpRules');
    }
    /**
     * @depends testExtImpRules
     */
    public function testIsAllowed() {
        $handler = RulesSensitiveToTheContext::getInstance();
        $this->assertTrue($handler->isAllowed(1), "Rule should Allow");
        $this->assertFalse($handler->isAllowed(2), "Rule should not Allow");
    }   
}


class RSCextendRule extends Rule{}
class RSCimplementRule implements RuleSensitiveToTheContext{
	static function isAllowed(& $vm){}
    static function getWeight(){}
    static function isEnable(){}
    static function enable(){}
    static function disable(){}
}
class RSCextImpRules extends Rule implements RuleSensitiveToTheContext{
	static function isAllowed(& $vm){ 
		return ($vm === 1);
	}
}