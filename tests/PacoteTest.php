<?php

require_once "src/Pacote.php";
require_once "src/Objeto.php";

class PacoteTest extends PHPUnit_Framework_TestCase {
	
	public function testPacote()
	{
		$pac = new Pacote(4);

		$m = new Objeto("Moeda", 5);
		$co = new Objeto("Copo", 4);

		$this->assertEquals(4, $pac->getQtdLivre());
		$this->assertEquals(4, $pac->getTamanho());

		$this->assertEquals(false, $pac->itemCabe($m));
		$this->assertEquals(true, $pac->itemCabe($co));

		$pac->guardar($m);
		$pac->guardar($co);

		$this->assertEquals(0, $pac->getQtdLivre());
		$this->assertEquals(4, $pac->getTamanho());

		$pac->setQtdLivre(7);
		$pac->setTamanho(10);
		$pac->setQtdLivre(9);

		$this->assertEquals(9, $pac->getQtdLivre());
		$this->assertEquals(10, $pac->getTamanho());

		$pac->setTamanho(4);
		$pac->setQtdLivre(4);

		$this->assertEquals(4, $pac->getQtdLivre());
		$this->assertEquals(4, $pac->getTamanho());

		$p = new Objeto("Pão", 2);
		$s = new Objeto("Sabão", 3);
		$c = new Objeto("Caderno", 2);
		$l = new Objeto("Lupa", 4);
		$t = new Objeto("Travesseiro", 1);

		$pac2 = new Pacote(4);
		$pac3 = new Pacote(4);

		$pac->guardar($p);
		$this->assertEquals(false, $pac->itemCabe($s));
		
	}
}