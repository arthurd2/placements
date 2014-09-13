<?php

class Pacote {
    public $qtdLivre;
    public $tamanho;
    private $posPac = 0;
    public $itens = array();

    function __construct($tamanho) {
        $this->tamanho = $tamanho;
        $this->qtdLivre = $tamanho;
    }

    public function getQtdLivre() {
        return $this->qtdLivre;
    }

    public function setQtdLivre($novaQtd) {
        if($this->getTamanho() < $novaQtd){
            echo "Quantidade livre não pode ser maior que o tamanho! \n";
        } else {
            $this->qtdLivre = $novaQtd;
        }
    }

    public function getTamanho() {
        return $this->tamanho;
    }

    public function setTamanho($novoTam) {
        $this->tamanho = $novoTam;
    }

    public function itemCabe($item) {
        if($item->dimensao > $this->qtdLivre){
            return false;
        }
        return true;
    }

    public function guardar($item) {
        if (! $this->itemCabe($item)) {
            echo "Espaço livre insuficiente para: " . $item->getNome() . "\n";
        } else {
            $this->itens[$this->posPac] = $item;
            $this->posPac++;
            $this->qtdLivre -= $item->dimensao;
        }
    }
}
