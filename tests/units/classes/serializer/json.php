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

	function testTypeIs()
	{
		$this
			->given(
				$type = new object\type('foo')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($type) { $this->testedInstance->typeIs($type); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testStringPropertyHasValue()
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
				->exception(function() use ($property, $string1) { $this->testedInstance->stringPropertyHasValue($property, $string1); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testIntegerPropertyHasValue()
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
				->exception(function() use ($property, $integer1) { $this->testedInstance->integerPropertyHasValue($property, $integer1); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testFloatPropertyHasValue()
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
				->exception(function() use ($property, $float1) { $this->testedInstance->floatPropertyHasValue($property, $float1); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testBooleanPropertyHasValue()
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
				->exception(function() use ($property, $boolean1) { $this->testedInstance->booleanPropertyHasValue($property, $boolean1); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testStorablePropertyHasValue()
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
				->exception(function() use ($property, $storable1) { $this->testedInstance->storablePropertyHasValue($property, $storable1); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
		;
	}

	function testNullProperty()
	{
		$this
			->given(
				$property = new object\property('foo')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property) { $this->testedInstance->nullProperty($property); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Data consumer is undefined')
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
				$this->calling($storable1)->storerIsReady = function($serializer) {}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{}'))->once

			->if(
				$type = new object\type('aType'),
				$this->calling($storable1)->storerIsReady = function($serializer) use ($type) {
					$serializer->typeIs($type);
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
				$this->calling($storable1)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
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
				$this->calling($storable1)->storerIsReady = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerPropertyHasValue($integer1Property, $integer1);
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
				$this->calling($storable1)->storerIsReady = function($serializer) use ($float1Property, $float1) {
					$serializer->floatPropertyHasValue($float1Property, $float1);
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
				$this->calling($storable1)->storerIsReady = function($serializer) use ($boolean1Property, $boolean1) {
					$serializer->booleanPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"boolean1Property":false}'))->once

			->given(
				$boolean1 = new object\property\boolean(true)
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($boolean1Property, $boolean1) {
					$serializer->booleanPropertyHasValue($boolean1Property, $boolean1);
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
				$this->calling($storable2)->storerIsReady = function($serializer) use ($integer1Property, $integer1) {
					$serializer->integerPropertyHasValue($integer1Property, $integer1);
				},
				$this->calling($storable1)->storerIsReady = function($serializer) use ($storable1Property, $storable2) {
					$serializer->storablePropertyHasValue($storable1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"storable1Property":{"integer1Property":666}}'))->once

			->given(
				$array1Property = new object\property('array1Property')
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($array1Property, $storable2) {
					$serializer->arrayPropertyHasValues($array1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"array1Property":[{"integer1Property":666}]}'))->once

			->given(
				$storable3 = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($storable3)->storerIsReady = function($serializer) use ($float1Property, $float1) {
					$serializer->floatPropertyHasValue($float1Property, $float1);
				},
				$this->calling($storable1)->storerIsReady = function($serializer) use ($array1Property, $storable2, $storable3) {
					$serializer->arrayPropertyHasValues($array1Property, $storable2, $storable3);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"array1Property":[{"integer1Property":666},{"float1Property":666.999}]}'))->once

			->given(
				$null1Property = new object\property('null1Property')
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($null1Property) {
					$serializer->nullProperty($null1Property);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedStorable($dataConsumer, $storable1))->isTestedinstance
				->mock($dataConsumer)->receive('newData')->withArguments(new data\data('{"null1Property":null}'))->once

			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use (
						$string1Property, $string1,
						$integer1Property, $integer1,
						$float1Property, $float1,
						$boolean1Property, $boolean1,
						$storable1Property, $storable2,
						$array1Property,
						$null1Property
					) {
					$serializer
						->stringPropertyHasValue($string1Property, $string1)
						->integerPropertyHasValue($integer1Property, $integer1)
						->floatPropertyHasValue($float1Property, $float1)
						->booleanPropertyHasValue($boolean1Property, $boolean1)
						->storablePropertyHasValue($storable1Property, $storable2)
						->arrayPropertyHasValues($array1Property, $storable2)
						->nullProperty($null1Property)
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
