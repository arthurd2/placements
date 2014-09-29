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
        $vm1 = new VM(VM_NAME, $this->dimensions);
        $vm2 = new VM(VM_NAME2, $this->small_dimensions);
        $vm3 = new VM(VM_NAME3, $this->small_dimensions);
        $host1 = new Host(HOST_NAME, $this->dimensions);
        $host2 = new Host(HOST_NAME2, $this->dimensions);
        $host3 = new Host(HOST_NAME3, $this->dimensions);

        $this->host3 = $host3;
        $this->vm3 = $vm3;

        $this->vms = array($vm1,$vm2);
        $this->hosts = array($host1,$host2);
        
        $this->matrix[$host1->getId()][$vm1->getId()] = true;
        $this->matrix[$host1->getId()][$vm2->getId()] = false;
        //$this->matrix[$host2->getId()][$vm1->getId()] = true; // Nao existe de proposito.
        $this->matrix[$host2->getId()][$vm2->getId()] = true;
        
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
        $this->assertTrue($this->rule->isPermited($this->hosts[0], $this->vms[0]));
    }
    
    function testVMNaoPermitida() {
        $this->assertFalse($this->rule->isPermited($this->hosts[0], $this->vms[1]));
    }
    
    function testVMInexistenteNaoPermitida() {
        $this->assertFalse($this->rule->isPermited($this->hosts[0], $this->vm3));
    }
    
    function testHostInexistenteNaoPermitida() {
        $this->assertFalse($this->rule->isPermited($this->host3, $this->vms[1]));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage TestingWithoutMatrix
     */
    function testIsPermitterWithoutMatrix() {
        $rule = new Rule();
        $rule->isPermited($this->host3, $this->vms[1]);
    }
    
    //Testar merge do mesmo tamanho
    function testMergedRulesOfTheSameSize() {
        $rules = array($this->rule, $this->rule);
        $matrix = $this->matrix;
        $matrix[$this->hosts[1]->getId()][$this->vms[0]->getId()] = false;
        $rule = new Rule($matrix);

        $merged = Rule::mergeRules($this->hosts, $this->vms, $rules);
        
        $this->assertEquals($rule, $merged , 'Merge de regras iguais');
    }
}
