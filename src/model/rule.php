<?php

class Rule {
	protected $matrix;

	function setMatrix($matrix){
		if(isset($this->matrix))
			return false;
		$this->matrix = $matrix;
		return true;
	}

	function isPermited($host,$vm){
		if (is_null($this->matrix)) throw new Exception("TestingWithoutMatrix", 1);
		
		return isset($this->matrix[$host][$vm])? $this->matrix[$host][$vm] : false ; 
	}

}