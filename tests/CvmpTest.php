<?php


class CvmpTest extends PHPUnit_Framework_TestCase
{
   
    public function testCvmpBuildCVmpByPlacements() {

    	$places = [
    	'v1'=>'p1',
    	'v2'=>'p1',
    	'v3'=>'p2',
    	'v4'=>'p2',
    	'v5'=>'p3',
    	];
        $cvmp = Cvmp::buildCVmpByPlacements($places) ;

        $this->assertEquals(5, $cvmp['nvms'], "# vms !match");
        $this->assertEquals(3, $cvmp['npms'], "# pms !match");
        foreach ($places as $vm => $pm) 
        	$this->assertEquals($pm, $cvmp['vmp'][$vm] , "PM !match");
        
        foreach ($places as $vm => $pm) 
        	$this->assertTrue( in_array($vm,$cvmp['pmp'][$pm]) , "VM !match");
        
        $this->assertEquals(2, $cvmp['rpm']['p1'], "# vms !match on RPM");
        $this->assertEquals(2, $cvmp['rpm']['p2'], "# vms !match on RPM");
        $this->assertEquals(1, $cvmp['rpm']['p3'], "# vms !match on RPM");
        return $cvmp;
    }

	/**
     * @depends testCvmpBuildCVmpByPlacements
     */
    public function testRemove($cvmp) {
        $vm = 'v1';
        $places = [
    	'v2'=>'p1',
    	'v3'=>'p2',
    	'v4'=>'p2',
    	'v5'=>'p3',
    	];
        Cvmp::removeVm($cvmp,$vm);
        $this->assertEquals(4, $cvmp['nvms'], "# vms !match");
        $this->assertEquals(3, $cvmp['npms'], "# pms !match");
        foreach ($places as $vm => $pm) 
        	$this->assertEquals($pm, $cvmp['vmp'][$vm] , "PM !match");
        
        foreach ($places as $vm => $pm) 
        	$this->assertTrue( in_array($vm,$cvmp['pmp'][$pm]) , "VM !match");
        
        $this->assertEquals(1, $cvmp['rpm']['p1'], "# vms !match on RPM");
        $this->assertEquals(2, $cvmp['rpm']['p2'], "# vms !match on RPM");
        $this->assertEquals(1, $cvmp['rpm']['p3'], "# vms !match on RPM");
        return $cvmp;
    }

	/**
     * @depends testRemove
     */
    public function testRemoveAgain($cvmp) {
        $vm = 'v1';
        $places = [
    	'v2'=>'p1',
    	'v3'=>'p2',
    	'v4'=>'p2',
    	'v5'=>'p3',
    	];
        Cvmp::removeVm($cvmp,$vm);
        $this->assertEquals(4, $cvmp['nvms'], "# vms !match");
        $this->assertEquals(3, $cvmp['npms'], "# pms !match");
        foreach ($places as $vm => $pm) 
        	$this->assertEquals($pm, $cvmp['vmp'][$vm] , "PM !match");
        
        foreach ($places as $vm => $pm) 
        	$this->assertTrue( in_array($vm,$cvmp['pmp'][$pm]) , "VM !match");
        
        $this->assertEquals(1, $cvmp['rpm']['p1'], "# vms !match on RPM");
        $this->assertEquals(2, $cvmp['rpm']['p2'], "# vms !match on RPM");
        $this->assertEquals(1, $cvmp['rpm']['p3'], "# vms !match on RPM");
        return $cvmp;
    }

	/**
     * @depends testRemoveAgain
     */
    public function testAdd($cvmp) {
        $vm = 'v1';
        $pm = 'p1';
        $places = [
        'v1'=>'p1',
    	'v2'=>'p1',
    	'v3'=>'p2',
    	'v4'=>'p2',
    	'v5'=>'p3',
    	];
        Cvmp::addVm($cvmp,$vm,$pm);
        $this->assertEquals(5, $cvmp['nvms'], "# vms !match");
        $this->assertEquals(3, $cvmp['npms'], "# pms !match");
        foreach ($places as $vm => $pm) 
        	$this->assertEquals($pm, $cvmp['vmp'][$vm] , "PM !match");
        
        foreach ($places as $vm => $pm) 
        	$this->assertTrue( in_array($vm,$cvmp['pmp'][$pm]) , "VM !match");
        
        $this->assertEquals(2, $cvmp['rpm']['p1'], "# vms !match on RPM");
        $this->assertEquals(2, $cvmp['rpm']['p2'], "# vms !match on RPM");
        $this->assertEquals(1, $cvmp['rpm']['p3'], "# vms !match on RPM");
        return $cvmp;
    }
    	/**
     * @depends testAdd
     */
    public function testAddAgain($cvmp) {
        $vm = 'v1';
        $pm = 'p1';
        $places = [
        'v1'=>'p1',
    	'v2'=>'p1',
    	'v3'=>'p2',
    	'v4'=>'p2',
    	'v5'=>'p3',
    	];
        Cvmp::addVm($cvmp,$vm,$pm);
        $this->assertEquals(5, $cvmp['nvms'], "# vms !match");
        $this->assertEquals(3, $cvmp['npms'], "# pms !match");
        foreach ($places as $vm => $pm) 
        	$this->assertEquals($pm, $cvmp['vmp'][$vm] , "PM !match");
        
        foreach ($places as $vm => $pm) 
        	$this->assertTrue( in_array($vm,$cvmp['pmp'][$pm]) , "VM !match");
        
        $this->assertEquals(2, $cvmp['rpm']['p1'], "# vms !match on RPM");
        $this->assertEquals(2, $cvmp['rpm']['p2'], "# vms !match on RPM");
        $this->assertEquals(1, $cvmp['rpm']['p3'], "# vms !match on RPM");
    }
}