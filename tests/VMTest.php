<?php

//require_once "./tests/constants.php";
require_once "./src/model/VM.php";
require_once "./src/model/Host.php";
require_once "tests/constants.php";


class VMTest extends PHPUnit_Framework_TestCase
{
    protected $DIMENSION = array(D_RAM => D_RAM_V, D_PROC => 1);
    protected $DIMENSION2 = array(D_RAM => D_RAM_V2, D_PROC => 2);
    protected $invalid_dimensions_values = array(null, -1 , 'string');
    protected $valid_dimensions_values = array(1, 0 , 1.2 , 1.0 , 0.1);
    protected $VM;
    
    function setUp() {
        $this->VM = new VM(VM_NAME, $this->DIMENSION);
    }
    
    public function testGetSetName() {
        $this->assertEquals(VM_NAME, $this->VM->getName());
        $this->VM->setName(VM_NAME2);
        $this->assertEquals(VM_NAME2, $this->VM->getName());
    }
    
    public function testGetSetDimensions() {
        $this->assertEquals($this->DIMENSION, $this->VM->getDimensions());
        $this->VM->setDimensions($this->DIMENSION2);
        $this->assertEquals($this->DIMENSION2, $this->VM->getDimensions());
    }
    
    public function testGetSetDimension() {
        $this->assertEquals(D_RAM_V, $this->VM->getDimension(D_RAM));
        $this->VM->setDimension(D_RAM, D_RAM_V2);
        $this->assertEquals(D_RAM_V2, $this->VM->getDimension(D_RAM));
    }
    
    /**
     * @expectedException Exception
     * @expectedExceptionMessage InvalidDimension
     */
    public function testGetSetInvalidDimension() {
        foreach ($this->invalid_dimensions_values as $value) {
            $this->VM->setDimension(D_RAM, $value);
            $this->assertEquals(D_RAM_V, $this->VM->getDimension(D_RAM));
        }
    }
    
    public function testGetSetValidDimension() {
        foreach ($this->valid_dimensions_values as $value) {
            $this->VM->setDimension(D_RAM, $value);
            $this->assertEquals($value, $this->VM->getDimension(D_RAM));
        }
    }

    public function testPossibleHosts() {
        //Testa getQtdHosts
        $this->assertEquals(0, $this->VM->getQtdHosts());

        //Testa getPossibleHosts
        $this->assertEquals(array(), $this->VM->getPossibleHosts());

        $host1 = new Host('1',array('RAM' => 512));
        $host2 = new Host('2',array('RAM' => 512));
        $hosts[$host1->getId()] = $host1; 
        $hosts[$host2->getId()] = $host2; 

        //Adiciona host
        $this->VM->setPossibleHost($host1);
        //Adiciona um host ja existente
        $this->VM->setPossibleHost($host1);
        $this->assertEquals(1, $this->VM->getQtdHosts());

        $this->VM->setPossibleHost($host2);
        $this->assertEquals(2, $this->VM->getQtdHosts());

        $this->assertEquals(2, $this->VM->getQtdHosts());

        //Testa getPossibleHosts
        $this->assertEquals($hosts, $this->VM->getPossibleHosts());

        //Remove  
        $this->VM->removePossibleHost($host2);        
        $this->assertEquals(1, $this->VM->getQtdHosts());

        //Remove inexistente
        $this->VM->removePossibleHost($host2);        
        $this->assertEquals(1, $this->VM->getQtdHosts());

    }

    public function testGetSetHosts() {
        $host1 = new Host('1',array('RAM' => 512));

        $this->assertNull($this->VM->getHost());

        $this->VM->setHost($host1);
        $this->assertEquals($host1,$this->VM->getHost());
        $this->assertEquals($host1->getId(),$this->VM->getHost()->getId());
    }  
    public function testDestroyHost() {
        $host1 = new Host('1',array('RAM' => 512));

        $this->VM->setHost($host1);

        unset($host1);
        $this->assertNull($this->VM->getHost());
        
    }  

}
