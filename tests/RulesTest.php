<?php
require_once "src/model/rule.php";
require_once "src/model/VM.php";
require_once "src/model/Host.php";
require_once "tests/constants.php";

class RulesTests extends PHPUnit_Framework_TestCase
{
    protected $matrix = array();
    private $hosts;
    private $dimensions = array(D_RAM => D_RAM_V2, D_PROC => D_PROC_V2);
    private $small_dimensions = array(D_RAM => D_RAM_V, D_PROC => D_PROC_V);
    private $big_dimensions = array(D_RAM => D_RAM_V3, D_PROC => D_PROC_V2);
    
    function __construct() {
        
        $this->vms[] = new VM(VM_NAME, $this->dimensions);
        $this->vms[] = new VM(VM_NAME2, $this->small_dimensions);
        $this->hosts[] = new Host(HOST_NAME, $this->dimensions);
        $this->hosts[] = new Host(HOST_NAME2, $this->dimensions);
        
        $this->matrix[HOST_NAME][VM_NAME] = true;
        $this->matrix[HOST_NAME2][VM_NAME2] = true;
        $this->matrix[HOST_NAME][VM_NAME2] = false;
        $this->rule = new Rule();
    }
    
    function setUp() {
        $this->rule = new Rule();
        $this->rule->setMatrix($this->matrix);
    }
    
    function testSetMatrix() {
        $rule = new Rule();
        $this->assertTrue($rule->setMatrix($this->matrix));
        $this->assertFalse($rule->setMatrix($this->matrix));
    }
    
    function testVMPermitida() {
        $this->assertTrue($this->rule->isPermited(HOST_NAME, VM_NAME));
    }
    
    function testVMNaoPermitida() {
        $this->assertFalse($this->rule->isPermited(HOST_NAME, VM_NAME2));
    }
    
    function testVMInexistenteNaoPermitida() {
        $this->assertFalse($this->rule->isPermited(HOST_NAME, 'vm3'));
    }
    
    function testHostInexistenteNaoPermitida() {
        $this->assertFalse($this->rule->isPermited('host3', VM_NAME2));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage TestingWithoutMatrix
     */
    function testIsPermitterWithoutMatrix() {
        $rule = new Rule();
        $rule->isPermited('host3', VM_NAME2);
    }
    
    //Testar merge do mesmo tamanho
    function testMergedRulesOfTheSameSize() {
        $rules = array($this->rule, $this->rule);
        $matrix = $this->matrix;
        $matrix[HOST_NAME2][VM_NAME] = false;
        $rule = new Rule($matrix);
        $this->assertEquals($rule, Rule::mergeRules($this->hosts, $this->vms, $rules), 'Merge de regras iguais');
    }
}
