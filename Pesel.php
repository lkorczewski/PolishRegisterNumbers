<?php

namespace PolishRegisterNumbers;

class Pesel extends RegisterNumberAbstract {
	
	const VALIDITY_INVALID_BIRTH_DATE = 10;
	
	const SEX_UNDEFINED = -1;
	const SEX_MALE      = 0;
	const SEX_FEMALE    = 1;
	
	protected $length = 11; 
	protected $controlDigitMultipliers = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
	
	function isValid(){
		
		if(null !== $this->validity){
			return $this->isValid;
		}
		
		$isValid = $this->validateLength()
			&& $this->validateNumericness()
			&& $this->validateDate()
			&& $this->validateControlNumber()
		;
		
		$this->isValid = $isValid;
		
		if($isValid){
			$this->validity = self::VALIDITY_VALID;
		}
		
		return $isValid;
	}
	
	protected function validateControlNumber(){
		
		$sum = $this->sumMultipliedDigits();
		$lastSumDigit = $sum % 10;
		$controlDigit = $lastSumDigit == 0 ? 0 : 10 - $lastSumDigit;
		
		if($controlDigit == substr($this->number, $this->length - 1, 1)){
			return true;
		}
		
		$this->validity = self::VALIDITY_INVALID_CONTROL_DIGIT;
		return false;
	}
	
	protected function validateDate(){
		
		$centuryMap = [
			0 => 19,
			1 => 20,
			2 => 21,
			3 => 22,
			4 => 18,
		];
		
		$year            = (integer)substr($this->number, 0, 2);
		$centuryAndMonth = (integer)substr($this->number, 2, 2);
		$month           = $centuryAndMonth % 20;
		$century         = $centuryMap[(integer)($centuryAndMonth / 20)];
		$day             = (integer)substr($this->number, 4, 2);
		
		if(checkdate($month, $day, $century * 100 + $year)){
			return true;
		}
		
		$this->validity = self::VALIDITY_INVALID_BIRTH_DATE;
		return false;
	}
	
	function getSex(){
		
		if(isset($this->number{9})){
		
			$sexDigit = $this->number{9};
			
			if(in_array($sexDigit, ['1', '3', '5', '7', '9'], true)){
				return self::SEX_MALE;
			}
			
			if(in_array($sexDigit, ['0', '2', '4', '6', '8'], true)){
				return self::SEX_FEMALE;
			}
		}
		
		return self::SEX_UNDEFINED;
	}
	
}
