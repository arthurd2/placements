<?php
require_once "src/model/QuantidadeDeResultados.php";
require_once "src/model/Scenario.php";

class QuantidadeDeResultadosTest extends PHPUnit_Framework_TestCase
{
    
    public function testScenarioSemRegras() {
        $scenario['nvms'] = 10;
        $scenario['npms'] = 10;
        
        $resp = QuantidadeDeResultados::calcularSemRegras($scenario);
        $this->assertEquals($resp, pow(10, 10), "Numero de VMs esta errado.");
    }
    
    public function testScenarioComRegras() {

        $placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $scenario = Scenario::buildScenarioByPlacements($placements);
        $resp = QuantidadeDeResultados::calcularComRegras($scenario);
        $this->assertEquals(24,$resp);
    }

    public function testFact() {
        $resp = QuantidadeDeResultados::fact(5);
        $this->assertEquals(120,$resp);
       
    }
    /**
     * @depends testFact
     */
    public function testCombinacao() {
        $resp = QuantidadeDeResultados::calcCombination(52,5);
        $this->assertEquals(2598960,$resp);
        $resp = QuantidadeDeResultados::calcCombination(16,3);
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
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSub($scenario,3);
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
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMProd($scenario,2);
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
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSum($scenario,2);
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
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSubProdOthers($scenario,3);
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
        list($out,$in) = QuantidadeDeResultados::getOutsidersInsiders('3',$scenario);
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
        $this->assertEquals(1,QuantidadeDeResultados::getInsidersCombinations($insiders,0));
        $this->assertEquals(8,QuantidadeDeResultados::getInsidersCombinations($insiders,1));
        $this->assertEquals(23,QuantidadeDeResultados::getInsidersCombinations($insiders,2));
        $this->assertEquals(28,QuantidadeDeResultados::getInsidersCombinations($insiders,3));
        $insiders = array(2,1,2,1,1,1);
        $this->assertEquals(44,QuantidadeDeResultados::getInsidersCombinations($insiders,3));
        //$this->assertEquals(array(),QuantidadeDeResultados::_getInsidersCombinations($insiders,2));
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

        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMOutIn($scenario,3);
        $this->assertEquals(72,$resp,"# of approximation !match.");
        }
}
