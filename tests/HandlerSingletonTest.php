<?php
class HandlerSingletonTest extends PHPUnit_Framework_TestCase
{
	 /**
     * @expectedException Exception
     */
    public function testExceptionHandlerSingleton() {
        $handler = HandlerSingleton::getInstance();
    	$handler->add('xxx');
    }

    public function testAddHandlerSingleton() {
        $handler = HandlerSingleton::getInstance();
        $this->assertEquals('HandlerSingleton', get_class($handler) , "Class Does not match");
        $handler->add('HandlerSingletonTest');
        $classes = $handler->getClasses();
    	$this->assertEquals(1, count($classes) , "Size of classes differ");
    	$this->assertEquals('HandlerSingletonTest', array_pop($classes) , "Name of the class differ");
    }
}