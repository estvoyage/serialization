<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\object,
	estvoyage\data,
	mock\estvoyage\data\consumer,
	mock\estvoyage\object\storable
;

class json extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\serialization\serializer')
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
				$storable1 = new \mock\estvoyage\object\storable
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

	function testDataConsumerNeedStorable()
	{
		$this
			->given(
				$dataConsumer = new consumer,
				$this->newTestedInstance,
				$storable1 = new storable
			)

			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) {}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{}'))->once

			->if(
				$type = new object\type('aType'),
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($type) {
					$serializer->objectTypeIs($type);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{}'))->twice

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
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"string1Property":"a string 1"}'))->once

			->given(
				$integer1Property = new object\property('integer1Property'),
				$integer1 = new object\property\integer(666)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"integer1Property":666}'))->once

			->given(
				$float1Property = new object\property('float1Property'),
				$float1 = new object\property\float(666.999)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($float1Property, $float1) {
					$serializer->floatObjectPropertyHasValue($float1Property, $float1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"float1Property":666.999}'))->once

			->given(
				$boolean1Property = new object\property('boolean1Property'),
				$boolean1 = new object\property\boolean
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($boolean1Property, $boolean1) {
					$serializer->booleanObjectPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"boolean1Property":false}'))->once

			->given(
				$boolean1 = new object\property\boolean(true)
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($boolean1Property, $boolean1) {
					$serializer->booleanObjectPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"boolean1Property":true}'))->once

			->given(
				$storable1Property = new object\property('storable1Property'),
				$storable2 = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($storable2)->objectStorerIs = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerObjectPropertyHasValue($integer1Property, $integer1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($storable1Property, $storable2) {
					$serializer->storableObjectPropertyHasValue($storable1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"storable1Property":{"integer1Property":666}}'))->once

			->given(
				$array1Property = new object\property('array1Property')
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($array1Property, $storable2) {
					$serializer->arrayObjectPropertyHasValues($array1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"array1Property":[{"integer1Property":666}]}'))->once

			->given(
				$storable3 = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($storable3)->objectStorerIs = function($serializer) use ($float1Property, $float1) {
					$serializer->floatObjectPropertyHasValue($float1Property, $float1);
				},
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($array1Property, $storable2, $storable3) {
					$serializer->arrayObjectPropertyHasValues($array1Property, $storable2, $storable3);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"array1Property":[{"integer1Property":666},{"float1Property":666.999}]}'))->once

			->given(
				$null1Property = new object\property('null1Property')
			)
			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use ($null1Property) {
					$serializer->nullObjectProperty($null1Property);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedinstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"null1Property":null}'))->once

			->if(
				$this->calling($storable1)->objectStorerIs = function($serializer) use (
						$string1Property, $string1,
						$integer1Property, $integer1,
						$float1Property, $float1,
						$boolean1Property, $boolean1,
						$storable1Property, $storable2,
						$array1Property,
						$null1Property
					) {
					$serializer
						->stringObjectPropertyHasValue($string1Property, $string1)
						->integerObjectPropertyHasValue($integer1Property, $integer1)
						->floatObjectPropertyHasValue($float1Property, $float1)
						->booleanObjectPropertyHasValue($boolean1Property, $boolean1)
						->storableObjectPropertyHasValue($storable1Property, $storable2)
						->arrayObjectPropertyHasValues($array1Property, $storable2)
						->nullObjectProperty($null1Property)
					;
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)
					->receive('newData')
						->withArguments(new data\data(
								'{"string1Property":"a string 1","integer1Property":666,"float1Property":666.999,"boolean1Property":true,"storable1Property":{"integer1Property":666},"array1Property":[{"integer1Property":666}],"null1Property":null}'
							)
						)
							->once
		;
	}
}
