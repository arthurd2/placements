<?php
require_once "src/model/Approximation.php";
require_once "src/model/Scenario.php";

class ApproximationTest extends PHPUnit_Framework_TestCase
{
    
    public function testScenarioSemRegras() {
        $scenario['nvms'] = 10;
        $scenario['npms'] = 10;
        
        $resp = Approximation::calcularSemRegras($scenario);
        $this->assertEquals($resp, pow(10, 10), "Numero de VMs esta errado.");
    }
    
    public function testScenarioComRegras() {

        $placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = Approximation::calcularComRegras($scenario);
        $this->assertEquals(24,$resp);
    }

    public function testFact() {
        $resp = Approximation::fact(5);
        $this->assertEquals(120,$resp);
       
    }
    /**
     * @depends testFact
     */
    public function testCombinacao() {
        $resp = Approximation::calcCombination(52,5);
        $this->assertEquals(2598960,$resp);
        $resp = Approximation::calcCombination(16,3);
        $this->assertEquals(560,$resp);
    }

    /**
     * @depends testFact
     */
    public function testMontarSumario() {

        //TODO Remover isto mais tarde...
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmSub() {
        $placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = Approximation::calcularComRegrasMaxVMSub($scenario,3);
        $this->assertEquals(23,$resp);
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmProd() {
        $placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = Approximation::calcularComRegrasMaxVMProd($scenario,2);
        $this->assertEquals(18,$resp);
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmSum() {
        $placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = Approximation::calcularComRegrasMaxVMSum($scenario,2);
        $this->assertEquals(10,$resp);
    }

    /**
     * @depends testMontarSumario
     */
    public function testcalcularComRegrasMaxVMSubProdOthers() {
        $placements = array(
            array('A:1', 'A:2'), 
            array('B:2', 'B:3'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:2', 'E:3'),
            array('F:2', 'F:3'),
            );
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = Approximation::calcularComRegrasMaxVMSubProdOthers($scenario,3);
        $this->assertEquals(48,$resp);
    }

    /**
     * @depends testMontarSumario
     */
    public function testgetOutsidersInsiders() {

        $placements = array(
            array('A:1', 'A:2'), 
            array('B:1','B:2', 'B:3'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:3'),
            array('F:4'),
            array('G:1','G:2','G:3'),
            array('H:1','H:2','H:3','H:4'),
            );
        $scenario = Scenario::buildScenarioByPlacements($placements);

        $exp_insiders = array(2,1,0,2,3);
        list($out,$in) = Approximation::getOutsidersInsiders('3',$scenario);
        $this->assertEquals(4,$out,"# of Outsiders !match.");
        $this->assertEquals(array_sum($exp_insiders),array_sum($in),"Sum of Insiders !match");
        $this->assertEquals(array_product($exp_insiders),array_product($in),"Prod of Insiders !match");
        $this->assertEquals($exp_insiders,$in,"Array of insiders !match");
    }
    /**
     * @depends testgetOutsidersInsiders
     */
    public function testgetInsidersCombinations() {

        $insiders = array(2,1,0,2,3);
        $this->assertEquals(1,Approximation::getInsidersCombinations($insiders,0));
        $this->assertEquals(8,Approximation::getInsidersCombinations($insiders,1));
        $this->assertEquals(23,Approximation::getInsidersCombinations($insiders,2));
        $this->assertEquals(28,Approximation::getInsidersCombinations($insiders,3));
        $insiders = array(2,1,2,1,1,1);
        $this->assertEquals(44,Approximation::getInsidersCombinations($insiders,3));
        //$this->assertEquals(array(),Approximation::_getInsidersCombinations($insiders,2));
    }

    /**
     * @depends testgetInsidersCombinations
     */

    public function testcalcularComRegrasMaxVMOutIn() {

        $placements = array(
            array('A:1', 'A:2'), 
            array('B:2', 'B:3'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:2','E:3'),
            array('F:2','F:3'),
            array('G:1','G:3'),
            array('H:1','H:3'),
            );
        $scenario = Scenario::buildScenarioByPlacements($placements);

        $resp = Approximation::calcularComRegrasMaxVMOutIn($scenario,3);
        $this->assertEquals(72,$resp,"# of approximation !match.");
        }

     /**
     * @depends testcalcularComRegrasMaxVMOutIn
     */

    public function testCombinatorialSplitterApproach() {
        //http://placements.rede.ufsc.br:8888/?max=3&state={%22cols%22:[{%22label%22:%22VMs%22,%22type%22:%22string%22},{%22label%22:%22p0%22,%22type%22:%22boolean%22},{%22label%22:%22p1%22,%22type%22:%22boolean%22},{%22label%22:%22p2%22,%22type%22:%22boolean%22}],%22rows%22:[{%22c%22:[{%22v%22:%22v0%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v1%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v2%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v3%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v4%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v5%22},{%22v%22:false},{%22v%22:true},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v6%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v7%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v8%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]}]}   
        $placements = array(
            array('A:1', 'A:2'), 
            array('B:1', 'B:2'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:1', 'E:2'),
            array('F:2', 'F:3'),
            array('G:1', 'G:2'),
            array('H:1', 'H:3'),
            );
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $expect = array(40,2);
        $result = Approximation::getSequencialSliceQuantities($scenario,3);
        $this->assertEquals($expect,$result,"# of quantities !match.");
    }

     /**
     * @depends testcalcularComRegrasMaxVMOutIn
     */

    public function testSequencialSplitterApproach() {
        //http://placements.rede.ufsc.br:8888/?max=3&state={%22cols%22:[{%22label%22:%22VMs%22,%22type%22:%22string%22},{%22label%22:%22p0%22,%22type%22:%22boolean%22},{%22label%22:%22p1%22,%22type%22:%22boolean%22},{%22label%22:%22p2%22,%22type%22:%22boolean%22}],%22rows%22:[{%22c%22:[{%22v%22:%22v0%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v1%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v2%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v3%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v4%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v5%22},{%22v%22:false},{%22v%22:true},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v6%22},{%22v%22:true},{%22v%22:true},{%22v%22:false}]},{%22c%22:[{%22v%22:%22v7%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]},{%22c%22:[{%22v%22:%22v8%22},{%22v%22:true},{%22v%22:false},{%22v%22:true}]}]}   
        $placements = array(
            array('A:1', 'A:2'), 
            array('B:1', 'B:2'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:1', 'E:2'),
            array('F:2', 'F:3'),
            array('G:1', 'G:2'),
            array('H:1', 'H:3'),
            );
        $maxVM = 3;
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $expect = array(60,60,40,60,60,40,60,40);
        $result = Approximation::getCombinatorialSliceQuantities($scenario,$maxVM);
        $this->assertEquals(array_sum($expect),array_sum($result),"Sum # of quantities !match.");
        $this->assertEquals(array_product($expect),array_product($result),"Prod # of quantities !match.");

        $this->assertEquals(52.5,Approximation::calculateAvgCombSplitterApproach($scenario,$maxVM));

    }


    /**
     * @depends testgetInsidersCombinations
     */

    public function testtreeSearchApproach() {

        $placements = array(
            array('A:1', 'A:2'), 
            array('B:2', 'B:3'), 
            array('C:1', 'C:3'), 
            array('D:1', 'D:2'),
            array('E:2','E:3'),
            array('F:2','F:3'),
            array('G:1','G:3'),
            array('H:1','H:3'),
            );
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $maxVM = 3;
        $resp = Approximation::treeSearchApproach($scenario,$maxVM);
        $this->assertEquals(80,$resp,"# of approximation !match.");
        }

}
