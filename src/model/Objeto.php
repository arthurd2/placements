<?php

class Objeto {
    public $nome;
    public $dimensao;

    function __construct($nome, $dimensao) {
        $this->nome = $nome;
        $this->dimensao = $dimensao;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($novoNome){
        $this->nome = $novoNome;
    }

    public function getDimensao(){
        return $this->dimensao;
    }

    public function setDimensao($novaDim){
        $this->dimensao = $novaDim;
    }
}

