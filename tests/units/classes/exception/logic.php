<?php

namespace estvoyage\serialization\tests\units\exception;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units
;

class logic extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('logicException')
			->implements('estvoyage\serialization\exception')
		;
	}
}
