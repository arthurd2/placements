<?php
class RuleTest extends PHPUnit_Framework_TestCase
{
    public function testConstructRule() {
        $rule = Rule::getInstance();
    	$this->assertEquals(1, $rule->getWeight(), "Weight differs from default");
    	$this->assertTrue( $rule->isEnable(), "Status differs from default");
    	$rule->disable();
    	$this->assertFalse( $rule->isEnable(), "Status differs from False after disabling");
    	$rule->enable();
    	$this->assertTrue($rule->isEnable(), "Status differs from True after enabling");
    }
}
