<?php

namespace estvoyage\serialization\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\value\tests\units
;

class serialization extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\value\string')
		;
	}
}
