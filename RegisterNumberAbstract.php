<?php

namespace PolishRegisterNumbers;

abstract class RegisterNumberAbstract {
	
	const VALIDITY_VALID                 = 0;
	const VALIDITY_INVALID_LENGTH        = 1;
	const VALIDITY_INVALID_NUMERICNESS   = 2;
	const VALIDITY_INVALID_CONTROL_DIGIT = 3;
	
	protected $number;
	
	protected $isValid;
	protected $validity;
	protected $length = 0;
	protected $controlDigitMultipliers = [];
	
	function __construct($number){
		$this->number = (string)$number;
	}
	
	function isValid(){
		
		if(null == $this->validity){
			$this->isValid = $this->validateLength()
				&& $this->validateNumericness()
				&& $this->validateControlNumber()
			;
			
			if($this->isValid){
				$this->validity = self::VALIDITY_VALID;
			}
		}
		
		return $this->isValid;
	}
	
	function getValidity(){
		if(null === $this->validity){
			$this->isValid();
		}
		
		return $this->validity;
	}
	
	function toString(){
		return $this->number;
	}
	
	protected function validateLength(){
		$isValid = preg_match('/^.{' . $this->length . '}$/', $this->number);
		
		if(!$isValid){
			$this->validity = self::VALIDITY_INVALID_LENGTH;
		}
		
		return $isValid;
	}
	
	protected function validateNumericness(){
		$isValid = preg_match('/^[0-9]*$/', $this->number);
		
		if(!$isValid){
			$this->validity = self::VALIDITY_INVALID_NUMERICNESS;
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
	
	protected function sumMultipliedDigits(){
		$digits = str_split($this->number);
		
		$multiply = function($value, $multiplier){
			return $multiplier * $value;
		};
		$multipliedValues = array_map($multiply, $digits, $this->controlDigitMultipliers);
		$sum = array_sum($multipliedValues);
		
		return $sum;
	}
}
