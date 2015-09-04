<?php

class OrderCloud {
	private $rules;
	private $qualifiers;
	private $costs;

	public function __construct($currentCvmp){
		global $realCvmp;
		$realCvmp = $currentCvmp;
		//TODO  execute Load procedures from Qualifiers, Rules and Costs 

	}

	public function organize( $baseCvmp, $ignoreVMs = [], $isMainInteration = true){
		//TODO Test Me
		//TODO set realCvmp
		$pareto = [];
		//Select Lower
		$lowVM = $this->selectLowerVm($baseCvmp,$ignoreVMs);
		//generateCVMP
		$cvmps = $this->generateCVMP($baseCvmp,$lowVM);
		//foreach Possible CVMP
		foreach ($cvmps as $key => $cvmp){
			if($this->isNonDominant($baseCvmp,$cvmp)){
				$pareto[] = $this->organize($cvmp,$ignoreVMs,false);
			}
		}
		if(empty($pareto)){
			$sCvmp = $baseCvmp;
		}else{
			$pareto[] = $baseCvmp;
			$sCvmp = $this->getCvmpWithMaxCostBenefit($pareto);
		}
		//Check if ignoreVMs set will full
		$isTheLastRecursion = (count($ignoreVMs) >= $baseCvmp['nvms']-1) ;
		if( $isMainInteration && !$isTheLastRecursion  ){
			$ignoreVMs[] = $this->selectLowerVm($sCvmp, $ignoreVMs);
			$sCvmp = $this->organize($sCvmp,$ignoreVMs,true);
		}
		return $sCvmp;
	}


	public function getCvmpWithMaxCostBenefit(& $pareto){
		$cbMin = -1;
		$cvmpMin = null;

		foreach ($pareto as $cvmp ) {
			$cb = Qualifiers::getCostBenefit($cvmp);
			if ( $cb > $cbMin ){
				$cbMin = $cb;
				$cvmpMin = $cvmp;
			}
		}
		return $cvmpMin;
	}
	public function generateCVMP( $cvmp, $vm){
		//TODO prettify this
		$newCvmps = [];
		$pm = $cvmp['vmp'][$vm];
		Cvmp::removeVm($cvmp,$vm);
		$pms = $cvmp['pmp'];
		unset($pms[$pm]);
		$pms = array_keys($pms);		

		foreach ($pms as $pm) {
			if (RulesFreeOfContext::isAllowed($vm,$pm)){
				$newCvmp = $cvmp;
				Cvmp::addVm($newCvmp,$vm,$pm);
				if (RulesSensitiveToTheContext::isAllowed($newCvmp)){
					$newCvmps[] = $newCvmp;
				}
			}
		}
		return $newCvmps;
	}

	public function selectLowerVm( &$cvmp, &$ignoreVMs){
		$evalBase = Qualifiers::getEvaluation($cvmp);

		$ignore = array_flip($ignoreVMs);
		//TODO Valor max do int pode ser um problema pois vai estourar no 
		$valueMin = PHP_INT_MAX;

		$vmMin = null;

		foreach ($evalBase as $vm => $value) {
			if (!isset($ignore[$vm]) and ($value < $valueMin) ){
				$valueMin = $value;
				$vmMin = $vm;
			}
		}
		if(is_null($vmMin)){
			throw new Exception("Couldnt find lower VM because the lower value is grater than the biggest INT", 1);
		}
		return $vmMin;
		
	}

	public function isNonDominant( &$baseCvmp , &$candidateCvmp ){
		$evalBase = Qualifiers::getEvaluation($baseCvmp);
		$evalCand = Qualifiers::getEvaluation($candidateCvmp);
		$count = 0;
		foreach ($evalBase as $vm => $value) {
			$count += $evalCand[$vm] - $value;
			if($value > $evalCand[$vm]) {
				return false;
			}
		}
		return ($count != 0) ;
	}
}