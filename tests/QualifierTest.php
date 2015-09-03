<?php


class QualifierTest extends PHPUnit_Framework_TestCase
{

    public function testGetWeight() {

        $qual = Qualifier::getInstance();
        $this->assertEquals('Qualifier', get_class($qual), "Class Does not match");
        $this->assertEquals(1,$qual->getWeight(),'');
    }
}