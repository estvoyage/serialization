<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\object,
	estvoyage\data,
	mock\estvoyage\data as mockOfData,
	mock\estvoyage\object as mockOfObject
;

class json extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->implements('estvoyage\serialization\serializer')
			->implements('estvoyage\data\provider')
		;
	}

	function testObjectTypeIs()
	{
		$this
			->given(
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

	function testDataConsumerIs()
	{
		$this
			->given(
				$dataConsumer = new mockOfData\consumer
			)

			->if(
				$this->newTestedInstance
			)
			->then
				->object($this->testedInstance->dataConsumerIs($dataConsumer))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{}'))
							->once

			->given(
				$string1Property = new object\property('string1Property'),
				$string1 = new object\property\string('a string 1')
			)
			->if(
				$this->newTestedInstance
					->stringObjectPropertyHasValue($string1Property, $string1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"string1Property":"a string 1"}'))
							->once

			->given(
				$integer1Property = new object\property('integer1Property'),
				$integer1 = new object\property\integer(666)
			)
			->if(
				$this->newTestedInstance
					->integerObjectPropertyHasValue($integer1Property, $integer1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"integer1Property":666}'))
							->once

			->given(
				$float1Property = new object\property('float1Property'),
				$float1 = new object\property\float(666.999)
			)
			->if(
				$this->newTestedInstance
					->floatObjectPropertyHasValue($float1Property, $float1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"float1Property":666.999}'))
							->once

			->given(
				$boolean1Property = new object\property('boolean1Property'),
				$boolean1 = new object\property\boolean
			)
			->if(
				$this->newTestedInstance
					->booleanObjectPropertyHasValue($boolean1Property, $boolean1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"boolean1Property":false}'))
							->once

			->given(
				$boolean2Property = new object\property('boolean2Property'),
				$boolean2 = new object\property\boolean(true)
			)
			->if(
				$this->newTestedInstance
					->booleanObjectPropertyHasValue($boolean2Property, $boolean2)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"boolean2Property":true}'))
							->once

			->given(
				$storable1Property = new object\property('storable1Property'),
				$storable1 = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				},

				$this->newTestedInstance
					->storableObjectPropertyHasValue($storable1Property, $storable1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"storable1Property":{"integer1Property":666}}'))
							->once

			->given(
				$array1Property = new object\property('array1Property')
			)
			->if(
				$this->newTestedInstance
					->arrayObjectPropertyHasValues($array1Property, $storable1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"array1Property":[{"integer1Property":666}]}'))
							->once

			->given(
				$null1Property = new object\property('null1Property')
			)
			->if(
				$this->newTestedInstance
					->nullObjectProperty($null1Property)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"null1Property":null}'))
							->once

			->given(
				$storable2Property = new object\property('storable2Property'),
				$storable2 = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				},

				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$string1Property, $string1,
						$integer1Property, $integer1,
						$float1Property, $float1,
						$boolean1Property, $boolean1,
						$storable2Property, $storable2,
						$array1Property,
						$null1Property
					) {
					$serializer
						->stringObjectPropertyHasValue($string1Property, $string1)
						->integerObjectPropertyHasValue($integer1Property, $integer1)
						->floatObjectPropertyHasValue($float1Property, $float1)
						->booleanObjectPropertyHasValue($boolean1Property, $boolean1)
						->storableObjectPropertyHasValue($storable2Property, $storable2)
						->arrayObjectPropertyHasValues($array1Property, $storable2)
						->nullObjectProperty($null1Property)
					;
				},

				$this->newTestedInstance
					->storableObjectPropertyHasValue($storable1Property, $storable1)
						->dataConsumerIs($dataConsumer)
			)
			->then
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data('{"storable1Property":{"string1Property":"a string 1","integer1Property":666,"float1Property":666.999,"boolean1Property":false,"storable2Property":{"integer1Property":666},"array1Property":[{"integer1Property":666}],"null1Property":null}}'))
							->once
		;
	}
}
