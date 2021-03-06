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

	function testConstructor()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance)->isEqualTo($this->newTestedInstance(new \estvoyage\csv\generator\rfc4180))
		;
	}

	function testCsvGeneratorIs()
	{
		$this
			->given(
				$csvGenerator = new mockOfCsv\generator
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->csvGeneratorIs($csvGenerator))
					->isNotTestedInstance
					->isEqualTo($this->newTestedInstance($csvGenerator))
		;
	}

	function testDataConsumerIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$this->calling($csvGenerator = new mockOfCsv\generator)->newCsvRecord = $csvGeneratorAfterHeader = new mockOfCsv\generator,
				$this->calling($csvGeneratorAfterHeader)->newCsvRecord = $csvGeneratorAfterLine = new mockOfCsv\generator
			)
			->if(
				$this->newTestedInstance($csvGenerator)
			)
			->then
				->object($this->testedInstance->dataConsumerIs($dataConsumer))
					->isTestedInstance
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new \estvoyage\csv\record\line)
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new \estvoyage\csv\record\line)
							->once
				->mock($csvGeneratorAfterLine)
					->receive('dataConsumerIs')
						->withArguments($dataConsumer)
							->once
		;
	}

	function testObjectTypeIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,
				$type = new object\type('foo')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->objectTypeIs($type))->isTestedInstance
		;
	}

	function testStringObjectPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$string1 = new object\property\string('bar')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->stringObjectPropertyHasValue($property, $string1))->isTestedInstance
		;
	}

	function testIntegerObjectPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$integer1 = new object\property\integer(666)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->integerObjectPropertyHasValue($property, $integer1))->isTestedInstance
		;
	}

	function testFloatObjectPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$float1 = new object\property\float(666.999)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->floatObjectPropertyHasValue($property, $float1))->isTestedInstance
		;
	}

	function testBooleanObjectPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$boolean1 = new object\property\boolean(rand(0, 1) == 1)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->booleanObjectPropertyHasValue($property, $boolean1))->isTestedInstance
		;
	}

	function testStorableObjectPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$storable1 = new mockOfObject\storable
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->storableObjectPropertyHasValue($property, $storable1))->isTestedInstance
		;
	}

	function testNullObjectProperty()
	{
		$this
			->given(
				$property = new object\property('foo')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->nullObjectProperty($property))->isTestedInstance
		;
	}

	function testNewStorable()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer,

				$this->calling($csvGenerator = new mockOfCsv\generator)->newCsvRecord = $csvGeneratorAfterHeader = new mockOfCsv\generator,
				$this->calling($csvGeneratorAfterHeader)->newCsvRecord = $csvGeneratorAfterLine = new mockOfCsv\generator,

				$this->newTestedInstance($csvGenerator)
			)

			->if(
				$this->calling($storable1 = new mockOfObject\storable)->objectStorerIs = function($serializer) {}
			)
			->then
				->object($this->testedInstance->newStorable($storable1))
					->isNotTestedInstance
					->isInstanceOf($this->testedInstance)
				->mock($storable1)
					->receive('objectStorerIs')
						->withArguments($this->newTestedInstance($csvGenerator))
							->once

			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line)
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line)
							->once

			->given(
				$type = new object\type('aType'),
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($type) {
					$serializer->objectTypeIs($type);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line)
							->twice
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line)
							->twice

			->given(
				$string1Property = new object\property('string1Property'),
				$string1 = new object\property\string('a string 1'),
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('a string 1')))
							->once


			->given(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('string1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 1')))
							->once

			->given(
				$string2Property = new object\property('string2Property'),
				$string2 = new object\property\string('a string 2'),
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($string1Property, $string1, $string2Property, $string2) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
					$serializer->stringObjectPropertyHasValue($string2Property, $string2);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('string2Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 2')))
							->once

			->given(
				$storable1Property = new object\property('storable1Property'),
				$storable2 = new mockOfObject\storable,
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($string1Property, $string1) {
					$serializer->stringObjectPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($storable1Property, $storable2) {
					$serializer->storableObjectPropertyHasValue($storable1Property, $storable2);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('storable1Property.string1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('a string 1')))
							->twice

			->given(
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
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('storable1Property.string1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('a string 1'), new data\data('a string 1')))
							->twice

			->given(
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
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('string1Property'), new data\data('storable1Property.string1Property'), new data\data('string2Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
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
				$integer1 = new object\property\integer(666),
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$integer1Property, $integer1
					) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('integer1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('666')))
							->once

			->given(
				$float1Property = new object\property('float1Property'),
				$float1 = new object\property\float(666.999),
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$float1Property, $float1
					) {
					$serializer->floatObjectPropertyHasValue($float1Property, $float1);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('float1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('666.999')))
							->once

			->given(
				$boolean1Property = new object\property('boolean1Property'),
				$boolean1 = new object\property\boolean(false),
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$boolean1Property, $boolean1
					) {
					$serializer->booleanObjectPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable1)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('boolean1Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('0')))
							->once

			->given(
				$boolean2Property = new object\property('boolean2Property'),
				$boolean2 = new object\property\boolean(true),
				$this->calling($storable2)->objectStorerIs = function($serializer) use (
						$boolean2Property, $boolean2
					) {
					$serializer->booleanObjectPropertyHasValue($boolean2Property, $boolean2);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable2)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('boolean2Property')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('1')))
							->once

			->given(
				$nullProperty = new object\property('nullProperty'),
				$this->calling($storable2)->objectStorerIs = function($serializer) use (
						$nullProperty
					) {
					$serializer->nullObjectProperty($nullProperty);
				}
			)
			->if(
				$this->testedInstance->newStorable($storable2)->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($csvGenerator)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('nullProperty')))
							->once
				->mock($csvGeneratorAfterHeader)
					->receive('newCsvRecord')
						->withArguments(new record\line(new data\data('')))
							->once
		;
	}
}
