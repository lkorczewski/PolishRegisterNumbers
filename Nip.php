<?php

namespace PolishRegisterNumbers;

class Nip extends RegisterNumberAbstract {
	
	protected $length = 10;
	protected $controlDigitMultipliers = [6, 5, 7, 2, 3, 4, 5, 6, 7];
	
}
