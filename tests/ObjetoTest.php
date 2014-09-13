<?php

require_once "src/Objeto.php";

class ObjetoTest extends PHPUnit_Framework_TestCase {
	
	public function testObjeto() {

		$tel = new Objeto("Telefone", 2);

		$this->assertEquals("Telefone", $tel->getNome());
		$this->assertEquals(2, $tel->getDimensao());

		$tel->setNome("Torradeira");
		$tel->setDimensao(7);

		$this->assertEquals("Torradeira", $tel->getNome());
		$this->assertEquals(7, $tel->getDimensao());
	}
}