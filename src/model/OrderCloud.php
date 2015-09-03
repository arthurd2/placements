<?php

class OrderCloud {
	private $rules;
	private $qualifiers;
	private $costs;

	public function __construct(){
		//TODO  execute Load procedures from Qualifiers, Rules and Costs 

	}

	public function organize(CVMP $baseCvmp, $ignoreVMs = [], $isMainInteration = true){
		//TODO Test Me
		//TODO set realCvmp
		$pareto = [];
		//Select Lower
		$lowVM = $this->selectLowerVm($baseCvmp,$ignoreVMs);

		//generateCVMP
		$cvmps = $this->generateCVMP($baseCvmp,$lowVM);

		//foreach Possible CVMP
		foreach ($cvmps as $key => $cvmp) 
			//if isNonDominant
			if($this->isNonDominant($baseCvmp,$cvmp))
				//recursion
				$pareto[] = $this->organize($cvmp,$ignoreVMs,false);

		if(empty($pareto))
			$sCvmp = $baseCvmp;
		else{
			$pareto[] = $baseCvmp;
			$sCvmp = getCvmpWithMaxCostBenefit($pareto);
		}

		$isLastRecursion = ( count($ignoreVMs) == $baseCvmp['nvms'] );

		if( $isMainInteration and !$isLastRecursion  ){
			$ignoreVMs[] = $this->selectLowerVm($sCvmp, $ignoreVMs);
			$sCvmp = $this->organize($sCvmp,$ignoreVMs,true);
		}
		return $sCvmp;
	}


	public function getCvmpWithMaxCostBenefit(& $pareto){
		//TODO Test Me
		$cbMin = -1;
		$cvmpMin = null;

		foreach ($pareto as $cvmp ) {
			$cb = Qualifiers::getCostBenefit($cvmp);
			if ( $cb > $cbMin ){
				$cbMin = $cb;
				$cvmpMin = $cvmp;
			}
		}
		return $vm;
	}
	public function generateCVMP(CVMP $cvmp, $vm){
		//TODO Test Me
		$newCvmps = [];

		Cvmp::removeVm($cvmp,$vm);
		$pms = array_keys($cvmp['pmp']);		

		foreach ($pms as $pm) {
			if (RulesFreeOfContext::isAllowed($vm,$pm)){
				$newCvmp = $cvmp;
				Cvmp::addVm($newCvmp,$vm,$pm);
				if (RulesSensitiveToTheContext::isAllowed($newCvmp))
					$newCvmps[] = $newCvmp;
			}
		}
		return $newCvmps;
	}

	public function selectLowerVm(CVMP &$cvmp, &$ignoreVMs){
		//TODO Test Me
		$evalBase = Qualifiers::getEvaluation($cvmp);
		$ignore = array_flip($ignore);
		//TODO Valor max do int pode ser um problema pois vai estourar no 
		$valueMin = PHP_INT_MAX;

		$vmMin = null;

		foreach ($evalBase as $vm => $value) {
			if (!isset($ignore[$vm]) and ($value < $valueMin) ){
				$valueMin = $value;
				$vmMin = $vm;
			}
		}
		if(is_null($vmMin)) throw new Exception("Couldnt find lower VM because the lower value is grater than the biggest INT", 1);
		
		return $vm;
		
	}

	public function isNonDominant( &$baseCvmp , &$candidateCvmp ){
		//TODO Test Me
		$evalBase = Qualifiers::getEvaluation($baseCvmp);
		$evalCand = Qualifiers::getEvaluation($candidateCvmp);

		foreach ($evalBase as $vm => $value) {
			if($value > $evalCand[$vm])
				return false;
		}
		return true;
	}
}

$x = new OrderCloud();