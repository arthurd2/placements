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
    public function testToGoogleTableLines() {
        $scenario['rvm'] = array('A'=>2,'B'=>2,'C'=>2,'D'=>3);
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $scenario['placements'] = array(
            array('A:2', 'A:3'), 
            array('B:1', 'B:2'), 
            array('C:2', 'C:3'), 
            array('D:1', 'D:2', 'D:3'));
        $expect = 
"data.addRows([
['A',false,true,true],
['B',true,true,false],
['C',false,true,true],
['D',true,true,true]
]);";

        $resp = Scenario::toGoogleTableLines($scenario);
        $this->assertEquals($expect,$resp);
    }
    public function testToGoogleTableHeader() {
        $expect=
"data.addColumn('string', 'Name');
data.addColumn('boolean', '1');
data.addColumn('boolean', '2');
data.addColumn('boolean', '3');\n";
        $scenario['rpm'] = array('1'=>2,'2'=>4,'3'=>3);
        $resp = Scenario::toGoogleTableHeader($scenario);
        $this->assertEquals($expect,$resp);
    }
}