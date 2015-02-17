<?php

namespace estvoyage\serialization\tests\units;

require __DIR__ . '/../runner.php';

use
	estvoyage\serialization\tests\units
;

class serializer extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\object\storer')
			->isAbstract
		;
	}
}
