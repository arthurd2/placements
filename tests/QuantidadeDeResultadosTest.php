<?php
require_once "src/model/QuantidadeDeResultados.php";

class QuantidadeDeResultadosTest extends PHPUnit_Framework_TestCase
{
    
    public function testScenarioSemRegras() {
        $scenario['nvms'] = 10;
        $scenario['npms'] = 10;
        
        $resp = QuantidadeDeResultados::calcularSemRegras($scenario);
        $this->assertEquals($resp, pow(10, 10), "Numero de VMs esta errado.");
    }
    
    public function testScenarioComRegras() {

        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
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
        $resp = QuantidadeDeResultados::calcCombination(52,5);
        $this->assertEquals(2598960,$resp);
        $resp = QuantidadeDeResultados::calcCombination(16,3);
        $this->assertEquals(560,$resp);
        
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmSub() {
        $scenario['rvm'] = array('A'=>2,'B'=>2,'C'=>2,'D'=>3);
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSub($scenario,3);
        $this->assertEquals(23,$resp);
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmProd() {
        $scenario['rvm'] = array('A'=>2,'B'=>2,'C'=>2,'D'=>3);
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMProd($scenario,2);
        $this->assertEquals(18,$resp);
    }
    /**
     * @depends testMontarSumario
     */
    public function testScenarioComRegrasMaxvmSum() {
        $scenario['rvm'] = array('A'=>2,'B'=>2,'C'=>2,'D'=>3);
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSum($scenario,2);
        $this->assertEquals(10,$resp);
    }

        public function testTemporario() {
        $scenario['rvm'] = array('A'=>2,'B'=>2,'C'=>2,'D'=>3);
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $resp = QuantidadeDeResultados::calcularComRegrasMaxVMSum($scenario,2);
        $this->assertEquals(10,$resp);
    }
}
