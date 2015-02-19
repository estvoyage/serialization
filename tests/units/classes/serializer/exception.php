<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units
;

class exception extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('runtimeException')
			->implements('estvoyage\serialization\exception')
		;
	}
}
