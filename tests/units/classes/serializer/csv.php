<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\data,
	estvoyage\object,
	estvoyage\csv\record,
	mock\estvoyage\csv as mockOfCsv,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\object as mockOfObject
;

class csv extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\data\provider')
			->implements('estvoyage\serialization\serializer')
		;
	}

	function testDataConsumerIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$this->calling($csvGenerator = new mockOfCsv\generator)->dataConsumerIs = $csvGenerator
			)
			->if(
				$this->newTestedInstance(new mockOfData\consumer, $csvGenerator)
			)
			->then
				->object($this->testedInstance->dataConsumerIs($dataConsumer))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($dataConsumer, $csvGenerator))
		;
	}

	function testNewStorable()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,

				$csvGenerator = new mockOfCsv\generator,

				$this->calling($csvGenerator)->dataConsumerIs = $csvGenerator,
				$this->calling($csvGenerator)->newCsvRecord = $csvGenerator,

				$this->newTestedInstance($dataConsumer, $csvGenerator)
			)

			->if(
				$this->calling($storable1 = new mockOfObject\storable)->objectStorerIs = function($serializer) {}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->never

			->if(
				$type = new object\type('aType'),
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($type) {
					$serializer->objectTypeIs($type);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->never

			->given(
				$string1Property = new object\property('string1Property'),
				$string1 = new object\property\string('a string 1')
			)

			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property')))
							->once
						->withArguments(new record\line(new data\data('a string 1')))
							->once

			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('string1Property')))
							->once
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 1')))
							->once

			->given(
				$string2Property = new object\property('string2Property'),
				$string2 = new object\property\string('a string 2')
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1, $string2Property, $string2) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
					$serializer->stringObjectPropertyHasValue($string2Property, $string2);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('string2Property')))
							->once
						->withArguments(new record\line(new data\data('string1Property'), new data\data('string2Property')))
							->once

			->given(
				$storable1Property = new object\property('storable1Property'),
				$storable2 = new mockOfObject\storable
			)

			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($storable1Property, $storable2) {
					$serializer->storableObjectPropertyHasValue($storable1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('storable1Property.string1Property')))
							->once
						->withArguments(new record\line(new data\data('a string 1')))
							->twice

			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1, $storable1Property, $storable2) {
					$serializer
						->stringObjectPropertyHasValue($string1Property, $string1)
						->storableObjectPropertyHasValue($storable1Property, $storable2)
					;
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('storable1Property.string1Property')))
							->once
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 1')))
							->twice

			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$string1Property, $string1,
						$storable1Property, $storable2,
						$string2Property, $string2
					) {
					$serializer
						->stringObjectPropertyHasValue($string1Property, $string1)
						->storableObjectPropertyHasValue($storable1Property, $storable2)
						->stringObjectPropertyHasValue($string2Property, $string2)
					;
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('storable1Property.string1Property'), new data\data('string2Property')))
							->once
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 1'), new data\data('a string 2')))
							->once

			->given(
				$arrayProperty = new object\property('arrayProperty'),
				$storable2 = new mockOfObject\storable
			)

			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($arrayProperty, $storable2) {
					$serializer->arrayObjectPropertyHasValues($arrayProperty, $storable2);
				}
			)
			->then
				->exception(function() use ($dataConsumer, $storable1) { $this->testedInstance->newStorable($storable1); })
					->isInstanceOf('estvoyage\serialization\serializer\csv\exception')
					->hasMessage('Unable to serialize this kind of data')

			->given(
				$integer1Property = new object\property('integer1Property'),
				$integer1 = new object\property\integer(666)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$integer1Property, $integer1
					) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('integer1Property')))
							->once
						->withArguments(new record\line(new data\data('666')))
							->once

			->given(
				$float1Property = new object\property('float1Property'),
				$float1 = new object\property\float(666.999)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$float1Property, $float1
					) {
					$serializer->floatObjectPropertyHasValue($float1Property, $float1);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('float1Property')))
							->once
						->withArguments(new record\line(new data\data('666.999')))
							->once

			->given(
				$boolean1Property = new object\property('boolean1Property'),
				$boolean1 = new object\property\boolean(false)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$boolean1Property, $boolean1
					) {
					$serializer->booleanObjectPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('boolean1Property')))
							->once
						->withArguments(new record\line(new data\data('0')))
							->once

			->given(
				$boolean2Property = new object\property('boolean2Property'),
				$boolean2 = new object\property\boolean(true)
			)
			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use (
						$boolean2Property, $boolean2
					) {
					$serializer->booleanObjectPropertyHasValue($boolean2Property, $boolean2);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable2))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('boolean2Property')))
							->once
						->withArguments(new record\line(new data\data('1')))
							->once

			->given(
				$nullProperty = new object\property('nullProperty')
			)
			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use (
						$nullProperty
					) {
					$serializer->nullObjectProperty($nullProperty);
				}
			)
			->then
				->object($this->testedInstance->newStorable($storable2))->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('nullProperty')))
							->once
						->withArguments(new record\line(new data\data('')))
							->once
		;
	}
}
