<?php
require_once "src/model/VM.php";
require_once "src/model/Host.php";
require_once "tests/constants.php";


class PacoteTest extends PHPUnit_Framework_TestCase
{
    private $host;
	private $dimensions = array(D_RAM => D_RAM_V2 , D_PROC => D_PROC_V2);
	private $small_dimensions = array(D_RAM => D_RAM_V , D_PROC => D_PROC_V);
	private $big_dimensions = array(D_RAM => D_RAM_V3 , D_PROC => D_PROC_V2);

	function setUp(){
		$this->vm_equal = new VM('equal',$this->dimensions);
    	$this->vm_small = new VM('small',$this->small_dimensions);
    	$this->vm_big 	= new VM('big',$this->big_dimensions);
		$this->host = new Host($this->dimensions);
	}


    public function testGetSetDimensions() {
    	$this->assertEquals($this->dimensions, $this->host->getDimensions());
    	
    }
    
    //Teste Get Free Dimension
    public function testGetFreeDimensions(){
    	$this->assertEquals($this->dimensions, $this->host->getFreeDimensions());
    }

    public function testValidFitVM(){	
    	$this->assertTrue($this->host->fitVM($this->vm_equal),'VM igual serviu');
    	$this->assertTrue($this->host->fitVM($this->vm_small),'VM menor serviu');
    }

    public function testInvalidFitVM(){	
    	$this->assertFalse($this->host->fitVM($this->vm_big),'VM maior nao serviu');
    }

    public function testStoreVMSmall(){	
    	$this->assertTrue($this->host->storeVM($this->vm_small));
	}

    public function testStoreVMEqual(){	
    	$this->assertTrue($this->host->storeVM($this->vm_equal));
	}

    public function testStoreVMBig(){	
    	$this->assertFalse($this->host->storeVM($this->vm_big));
	}

    public function testStore3VM(){	
    	$this->assertTrue($this->host->storeVM($this->vm_small),'VM Small 1 Added');
		$this->assertTrue($this->host->storeVM($this->vm_small),'VM Small 2 Added');
		$this->assertFalse($this->host->storeVM($this->vm_small),'VM Small 3 Not Added');
		$expected = var_export(array($this->vm_small,$this->vm_small),true);
		$returned = var_export($this->host->getVMs(),true);
		$this->assertEquals($expected,$returned,'Returned VM ok!');
	}
    
}
