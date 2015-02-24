<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units
;

class generic extends units\test
{
	function testClass()
	{
		$this->testedClass
			->implements('estvoyage\serialization\serializer')
			->isAbstract
		;
	}
}
