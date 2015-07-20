<?php
require_once "src/model/Scenario.php";

class GerarScenariosTest extends PHPUnit_Framework_TestCase
{

    public function testGeraSenarioName() {
        $nvms = 10;
        $npms = 10;
        $apr = 0.5;

        list($places, $r_vm, $r_pm) = Scenario::geraScenario($apr,$nvms,$npms);
        $this->assertEquals($nvms,count($places),"Numero de VMs esta errado.");

        $total = 0;
        foreach ($places as $value)
            $total += count($value);
        
        $this->assertEquals($npms*$apr*$nvms,$total,"Numero final de estados esta errado.");
        $this->assertEquals(array_sum($r_vm),$total,"Numero final de estados diferente do r_vm.");
        $this->assertEquals(array_sum($r_pm),$total,"Numero final de estados diferente do r_pm.");
    }
}