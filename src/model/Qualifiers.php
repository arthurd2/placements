<?php
class Qualifiers extends HandlerSingleton {
    protected $interfaceClass = 'InterfaceQualifier';
    protected $extendsClass = 'Qualifier';

    static function getBenefit(& $cvmp){
    	//TODO Test Me
    	if (!isset($cvmp['qualifications']['benefit'])){
    		$evaluations = Qualifiers::getEvaluation($cvmp);
    		$cvmp['qualifications']['benefit'] = array_sum($evaluations);
    	}
  		return $cvmp['qualifications']['benefit'];
    }
    static function getEvaluation(& $cvmp){
        //TODO Test Me
        if (!isset($cvmp['qualifications']['values'])){
        	$qualifiers = Qualifiers::getClasses();
            foreach ($cvmp['vmp'] as $vm => $pm) 
                $cvmp['qualifications']['values'][$vm] =  1 ;
        	foreach ( $qualifiers as $class) {
        		$normEval = $class::evaluate($cvmp);
        		$w = $class::getWeight();
        		foreach ($normEval as $vm => $eval) {
                    $cvmp['qualifications']['values'][$vm] *=  pow($eval, $w);
                }
        	}
        }
        return $cvmp['qualifications']['values'];
    }

    static function getCostBenefit(& $cvmp){
    	//TODO Test Me
    	global $realCvmp;
    	if (!isset($cvmp['qualifications']['cb'])){
    		$candBen = Qualifiers::getBenefit($cvmp);
    		$realBen = Qualifiers::getBenefit($realCvmp);
    		$cvmp['qualifications']['cb'] = ($candBen-$realBen)/Costs::getCost($cvmp);
    	}
       	return $cvmp['qualifications']['cb']  ;
    }
}