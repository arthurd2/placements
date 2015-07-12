<?php
require_once "src/model/Scenario.php";

class GerarScenariosTest extends PHPUnit_Framework_TestCase
{

    public function testGeraSenarioName() {
        $nvms = 10;
        $npms = 10;
        $apr = 0.5;

        $scenario = Scenario::gera_scenario($apr,$nvms,$npms);
        $this->assertEquals($nvms,count($scenario),"Numero de VMs esta errado.");

        $total = 0;
        foreach ($scenario as $value)
            $total += count($value);
        
        $this->assertEquals($npms*$apr*$nvms,$total,"Numero final de stados esta errado.");
        
    }
}