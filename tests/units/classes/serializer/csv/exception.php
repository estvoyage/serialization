<?php

namespace estvoyage\serialization\tests\units\serializer\csv;

require __DIR__ . '/../../../runner.php';

use
	estvoyage\serialization\tests\units
;

class exception extends units\test
{
	function testClass()
	{
		$this->testedClass
			->extends('estvoyage\serialization\serializer\exception')
		;
	}
}
