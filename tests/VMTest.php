<?php

//require_once "./tests/constants.php";
require_once "./src/model/VM.php";
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
}
