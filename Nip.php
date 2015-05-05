<?php

namespace PolishRegisterNumbers;

class Nip extends RegisterNumberAbstract {
	
	protected $length = 10;
	protected $controlDigitMultipliers = [6, 5, 7, 2, 3, 4, 5, 6, 7];
	
	protected function validateControlNumber(){
		$sum = $this->sumMultipliedDigits();
		$controlDigit = $sum % 11;
		
		if($controlDigit == $this->number{$this->length - 1}){
			return true;
		}
		
		$this->validity = self::VALIDITY_INVALID_CONTROL_DIGIT;
		return false;
	}
	
}
