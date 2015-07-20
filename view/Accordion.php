<?php

class Accordion {

	private $itens = array();
	private $fmt = "<h3>%s</h3><div style='font-size:xx-small'>%s</div>\n";

	function add($title,$body){
		$item = new stdClass();
		$item->title = $title;
		$item->body = $body;
		 
		$this->itens[] = $item;
	}

	function get(){
		$resp = '';
		foreach ($this->itens as $item) {
			$resp .= sprintf($this->fmt, $item->title, $item->body);
		}
		return $resp;
	}
}


