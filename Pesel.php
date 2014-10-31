<?php

namespace PolishRegisterNumbers;

class Pesel {
	
	protected $pesel;
	protected $isValid;
	protected $validity;
	
	const VALIDITY_VALID                          = 0;
	const VALIDITY_INVALID_NUMERICNESS_AND_LENGTH = 1;
	const VALIDITY_INVALID_BIRTH_DATE             = 2;
	const VALIDITY_INVALID_CONTROL_DIGIT          = 3;
	
	const SEX_UNDEFINED = -1;
	const SEX_MALE      = 0;
	const SEX_FEMALE    = 1;
	
	function __construct($pesel){
		$this->pesel = (string)$pesel;
	}
	
	function isValid(){
		
		if(null !== $this->validity){
			return $this->isValid;
		}
		
		$isValid = $this->validateNumericnessAndLength()
			&& $this->validateDate()
			&& $this->validateControlNumber();
		
		$this->isValid = $isValid;
		
		if($isValid){
			$this->validity = self::VALIDITY_VALID;
		}
		
		return $isValid;
	}
	
	protected function validateNumericnessAndLength(){
		$isValid = preg_match('/^[0-9]{11}$/', $this->pesel);
		
		if(!$isValid){
			$this->validity = self::VALIDITY_INVALID_NUMERICNESS_AND_LENGTH;
		}
		
		return $isValid;
	}
	
	protected function validateDate(){
		$centuryMap = [
			0 => '19',
			1 => '20',
			2 => '21',
			3 => '22',
			4 => '18',
		];
		
		$year            = substr($this->pesel, 0, 2);
		$centuryAndMonth = substr($this->pesel, 2, 2);
		$month           = $centuryAndMonth % 20;
		$century         = $centuryMap[(integer)($centuryAndMonth / 20)];
		$day             = substr($this->pesel, 4, 2);
		
		if(checkdate($month, $day, $century * 100 + $year)){
			return true;
		}
		
		$this->validity = self::VALIDITY_INVALID_BIRTH_DATE;
		return false;
	}
	
	protected function validateControlNumber(){
		$digits = str_split($this->pesel);
		$multipliers = [1, 3, 7, 9, 1, 3, 7, 9, 1, 3];
		$sum = $this->sumMultipliedValues($digits, $multipliers);
		$lastSumDigit = $sum % 10;
		$controlDigit = $lastSumDigit == 0 ? 0 : 10 - $lastSumDigit;
		
		if($controlDigit == $digits[10]){
			return true;
		}
		
		$this->validity = self::VALIDITY_INVALID_CONTROL_DIGIT;
		return false;
	}
	
	protected function sumMultipliedValues(
		array $values,
		array $multipliers
	){
		$multiply = function($value, $multiplier){
			return $multiplier * $value;
		};
		$multipliedValues = array_map($multiply, $values, $multipliers);
		$sum = array_sum($multipliedValues);
		
		return $sum;
	}
	
	function getValidity(){
		if(null === $this->validity){
			$this->isValid();
		}
		
		return $this->validity;
	}
	
	function getSex(){
		
		if(isset($this->pesel{9})){
		
			$sexDigit = $this->pesel{9};
			
			if(in_array($sexDigit, ['1', '3', '5', '7', '9'], true)){
				return self::SEX_MALE;
			}
			
			if(in_array($sexDigit, ['0', '2', '4', '6', '8'], true)){
				return self::SEX_FEMALE;
			}
		}
		
		return self::SEX_UNDEFINED;
	}
	
	function toString(){
		return $this->pesel;
	}
	
}

