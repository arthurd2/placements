<?php
require_once "src/model/QuantidadeDeResultados.php";

class QuantidadeDeResultadosTest extends PHPUnit_Framework_TestCase
{
    
    public function testScenarioSemRegras() {
        $scenario = new stdClass();
        $scenario->nvms = 10;
        $scenario->npms = 10;
        
        $resp = QuantidadeDeResultados::calcularSemRegras($scenario);
        $this->assertEquals($resp, pow(10, 10), "Numero de VMs esta errado.");
    }
    
    public function testScenarioComRegras() {
        $scenario = new stdClass();
        $scenario->placements = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $resp = QuantidadeDeResultados::calcularComRegras($scenario);
        $this->assertEquals(24,$resp);
    }
}
