<?php

require_once __DIR__ . '/../RegisterNumberAbstract.php';
require_once __DIR__ . '/../Nip.php';

use PolishRegisterNumbers\Nip;

class NipTest extends PHPUnit_Framework_TestCase {
	
	function testIsValid(){
		$pesel = new Nip('6930751838');
		
		$this->assertEquals($pesel->isValid(), true);
		$this->assertEquals($pesel->getValidity(), Nip::VALIDITY_VALID);
	}
	
	function testIsValidIfPeselAsInteger(){
		$pesel = new Nip(6930751838);
		
		$this->assertEquals($pesel->isValid(), true);
		$this->assertEquals($pesel->getValidity(), Nip::VALIDITY_VALID);
	}
	
	function testInvalidLength(){
		$pesel = new Nip('69307518388');
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Nip::VALIDITY_INVALID_LENGTH);
	}
	
	function testInvalidCharacters(){
		$pesel = new Nip('abcdefgijk');
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Nip::VALIDITY_INVALID_NUMERICNESS);
	}
	
	function testInvalidControlDigit(){
		$pesel = new Nip('6930751839');
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Nip::VALIDITY_INVALID_CONTROL_DIGIT);
	}
}
