<?php


class OrderCloudTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @ depends testQUAextimp
     * @ expectedException Exception
     */
    public static function setUpBeforeClass(){
        foreach (Costs::getClasses() as $class) Costs::del($class);
        foreach (Qualifiers::getClasses() as $class) Qualifiers::del($class);
        foreach (RulesFreeOfContext::getClasses() as $class) RulesFreeOfContext::del($class);
        foreach (RulesSensitiveToTheContext::getClasses() as $class) RulesSensitiveToTheContext::del($class);
//        foreach (Qualifiers::getClasses() as $class) echo $class.PHP_EOL;

        Qualifiers::add('QUAmirror');        
    }


    public function testIsNonDominant() {

        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp1 = Cvmp::buildCVmpByPlacements($places);
        $cvmp2 = Cvmp::buildCVmpByPlacements($places);

        $cvmp1['mirror'] = 11;
        $cvmp2['mirror'] = 12;
        
        $oc = new OrderCloud(null);

        $this->assertTrue($oc->isNonDominant($cvmp1,$cvmp2));
        $this->assertFalse($oc->isNonDominant($cvmp1,$cvmp1));
        $this->assertFalse($oc->isNonDominant($cvmp2,$cvmp1));

    }
    public function testSelectLoweVM() {
        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp = Cvmp::buildCVmpByPlacements($places);
        
        $oc = new OrderCloud(null);
        $ivm = [];
        $this->assertEquals('v1',$oc->selectLowerVm($cvmp,$ivm));
        $ivm[] = 'v1';
        $this->assertEquals('v2',$oc->selectLowerVm($cvmp,$ivm));


    }
    /**
     * @depends testSelectLoweVM
     * @expectedException Exception
     */
    public function testSelectLoweVMWithIgnoreVmFull() {
        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp = Cvmp::buildCVmpByPlacements($places);
        
        $oc = new OrderCloud(null);
        $ivm = ['v1','v2','v3','v4','v5'];
        $oc->selectLowerVm($cvmp,$ivm);
    }
    public function testGenCVMPs() {
        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp = Cvmp::buildCVmpByPlacements($places);
        
        $oc = new OrderCloud(null);
        RFCextImpRules::disable();
        RSCextImpRules::disable();

        $cvmps = $oc->generateCVMP($cvmp,'v1');
        $this->assertEquals(2,count($cvmps));

        RFCextImpRules::enable();
        RSCextImpRules::enable();
    }

    public function testgetCvmpWithMaxCostBenefit() {
        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp0 = Cvmp::buildCVmpByPlacements($places);
        $cvmp1 = Cvmp::buildCVmpByPlacements($places);
        $cvmp2 = Cvmp::buildCVmpByPlacements($places);

        $cvmp1['mirror'] = 11;
        $cvmp2['mirror'] = 12;
        
        $oc = new OrderCloud($cvmp0);
        $pareto = [$cvmp1,$cvmp2];
        $best = $oc->getCvmpWithMaxCostBenefit($pareto);
        unset($best['qualifications']);
        $this->assertEquals($cvmp2,$best);
    }
    /**
     * @ depends xxx
     */
    public function testOrganize() {
        $places = ['v1' => 'p1', 'v2' => 'p1', 'v3' => 'p2', 'v4' => 'p2', 'v5' => 'p3', ];
        $cvmp0 = Cvmp::buildCVmpByPlacements($places);
        
        $oc = new OrderCloud($cvmp0);
        
        $best = $oc->organize($cvmp0);
        unset($best['qualifications']);
        $this->assertEquals($cvmp0,$best);
    }
}