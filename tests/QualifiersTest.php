<?php


class QualifiersTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @expectedException Exception
     */
    public function testExceptionUnkownClass() {
        $handler = Qualifiers::getInstance();
        $this->assertEquals('Qualifiers', get_class($handler), "Class Does not match");
        $handler->add('xxx');
    }
    
    /**
     * @expectedException Exception
     */
    public function testExtendQua() {
        $handler = Qualifiers::getInstance();
        $handler->add('QUAext');
        $this->assertTrue(False,'Accept a non implemented InterfaceQualifier Class');
    }

    /**
     * @expectedException Exception
     */
    public function testImplementQua() {
        $handler = Qualifiers::getInstance();
        $handler->add('QUAimp');
        $this->assertTrue(False,'Accept a non Qualifier extended Class');
    }

	/**
     * @depends testImplementQua
     */
    public function testQUAextimp() {
        $handler = Qualifiers::getInstance();
        $handler->add('QUAextimp');
    }
    /**
     * @depends testQUAextimpp
     */
    public function testEvaluate() {
        $handler = Qualifiers::getInstance();
        $x = 1;
        $this->assertEquals(2,$handler->getCost($x), "Rule should Allow");
    }   
}


class QUAext extends Qualifier{}
class QUAimp implements InterfaceQualifier{
	static function getWeight(){}
    static function evaluate(& $cvmp){ }

}
class QUAextimp extends Qualifier implements InterfaceQualifier{
	static function evaluate(& $cvmp){ 
		return 2;
	}
}