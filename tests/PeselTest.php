<?php

require_once __DIR__ . '/../Pesel.php';

use PolishRegisterNumbers\Pesel;

class PeselTest extends PHPUnit_Framework_TestCase {
	
	function testIsValid(){
		$pesel = new Pesel('80052900176');
		
		$this->assertEquals($pesel->isValid(), true);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_VALID);
	}
		
	function testIsValidIfPeselAsInteger(){
		$pesel = new Pesel(80052900176);
		
		$this->assertEquals($pesel->isValid(), true);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_VALID);
	}
	
	function testInvalidCharacters(){
		$pesel = new Pesel('abcdefgijk');
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_NUMERICNESS_AND_LENGTH);
	}
	
	function testInvalidDateWrongDay(){
		$pesel = new Pesel('80043100000'); // 1980-04-31

		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
	}
	
	function testInvalidDateLeapYears(){

		$pesel = new Pesel('80022900000'); // 1900-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertNotEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
		
		$pesel = new Pesel('81022900000'); // 1900-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
	}
		
	function testInvalidDateLeapYearOnTurnOfCentury(){
		
		$pesel = new Pesel('00022900000'); // 1900-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
		
		$pesel = new Pesel('00222900000'); // 2000-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertNotEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
		
		$pesel = new Pesel('00422900000'); // 2100-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
		
		$pesel = new Pesel('00622900000'); // 2200-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
		
		$pesel = new Pesel('00822900000'); // 2300-02-29
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_BIRTH_DATE);
	}
	
	function testInvalidControlDigit(){
		$pesel = new Pesel('80052900177');
		
		$this->assertEquals($pesel->isValid(), false);
		$this->assertEquals($pesel->getValidity(), Pesel::VALIDITY_INVALID_CONTROL_DIGIT);
	}
	
	function testSexIfCorrectCharacter(){
		$pesel = new Pesel('80052900176');
		
		$this->assertEquals($pesel->getSex(), Pesel::SEX_MALE);
		
		$pesel = new Pesel(80052900176);
		
		$this->assertEquals($pesel->getSex(), Pesel::SEX_MALE);
	}
	
	function testSexIfNoCharacter(){
		$pesel = new Pesel('800529');
		
		$this->assertEquals($pesel->getSex(), Pesel::SEX_UNDEFINED);
		
		$pesel = new Pesel(800529);
		
		$this->assertEquals($pesel->getSex(), Pesel::SEX_UNDEFINED);
	}
	
	function testSexIfWrongCharacter(){
		$pesel = new Pesel('abcdefghijk');
		$this->assertEquals($pesel->getSex(), Pesel::SEX_UNDEFINED);
	}
	
}

