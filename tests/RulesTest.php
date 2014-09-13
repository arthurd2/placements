<?php

require "src/model/rule.php";

class RulesTests extends PHPUnit_Framework_TestCase {
	protected $matrix = array();

	function __construct (){
		
		$this->matrix['host1']['vm1'] = true;
		$this->matrix['host1']['vm1'] = true;
		$this->matrix['host2']['vm2'] = true;
		$this->matrix['host1']['vm2'] = false;
		$this->rule = new Rule();
	}

	function setUp(){
		$this->rule = new Rule();
		$this->rule->setMatrix($this->matrix);
	}

	function testSetMatrix(){
		$rule = new Rule();
		$this->assertTrue($rule->setMatrix($this->matrix));
		$this->assertFalse($rule->setMatrix($this->matrix));
	}

	function testVMPermitida(){
		$this->assertTrue($this->rule->isPermited('host1','vm1'));
	}

	function testVMNaoPermitida(){
		$this->assertFalse($this->rule->isPermited('host1','vm2'));
	}

	function testVMInexistenteNaoPermitida(){
		$this->assertFalse($this->rule->isPermited('host1','vm3'));
	}

	function testHostInexistenteNaoPermitida(){
		$this->assertFalse($this->rule->isPermited('host3','vm2'));
	}

    /**
     * @expectedException Exception
     * @expectedExceptionMessage TestingWithoutMatrix
     */
	function testIsPermitterWithoutMatrix(){
		$rule = new Rule();
		$rule->isPermited('host3','vm2');
	}
}