<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\serialization,
	estvoyage\object,
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
					->hasMessage('Serializer is not ready')
		;
	}

	function testStringPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$string = new object\property\string('bar')
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property, $string) { $this->testedInstance->stringPropertyHasValue($property, $string); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Serializer is not ready')
		;
	}

	function testIntegerPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$integer = new object\property\integer(666)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property, $integer) { $this->testedInstance->integerPropertyHasValue($property, $integer); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Serializer is not ready')
		;
	}

	function testFloatPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$float = new object\property\float(666.999)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property, $float) { $this->testedInstance->floatPropertyHasValue($property, $float); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Serializer is not ready')
		;
	}

	function testBooleanPropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$boolean = new object\property\boolean(rand(0, 1) == 1)
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property, $boolean) { $this->testedInstance->booleanPropertyHasValue($property, $boolean); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Serializer is not ready')
		;
	}

	function testStorablePropertyHasValue()
	{
		$this
			->given(
				$property = new object\property('foo'),
				$storable = new \mock\estvoyage\object\storable
			)
			->if(
				$this->newTestedInstance
			)
			->then
				->exception(function() use ($property, $storable) { $this->testedInstance->storablePropertyHasValue($property, $storable); })
					->isInstanceOf('estvoyage\serialization\exception\logic')
					->hasMessage('Serializer is not ready')
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
					->hasMessage('Serializer is not ready')
		;
	}

	function testSerialize()
	{
		$this
			->given(
				$this->newTestedInstance,
				$storable = new storable
			)

			->if(
				$this->calling($storable)->storerIsReady = function($serializer) {}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{}'))

			->if(
				$type = new object\type('aType'),
				$this->calling($storable)->storerIsReady = function($serializer) use ($type) {
					$serializer->typeIs($type);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{}'))

			->given(
				$stringProperty = new object\property('stringProperty'),
				$string = new object\property\string('a string')
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($stringProperty, $string) {
					$serializer->stringPropertyHasValue($stringProperty, $string);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"stringProperty":"a string"}'))

			->given(
				$integerProperty = new object\property('integerProperty'),
				$integer = new object\property\integer(666)
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($integerProperty, $integer) {
					$serializer->integerPropertyHasValue($integerProperty, $integer);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"integerProperty":666}'))

			->given(
				$floatProperty = new object\property('floatProperty'),
				$float = new object\property\float(666.999)
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($floatProperty, $float) {
					$serializer->floatPropertyHasValue($floatProperty, $float);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"floatProperty":666.999}'))

			->given(
				$booleanProperty = new object\property('booleanProperty'),
				$boolean = new object\property\boolean
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($booleanProperty, $boolean) {
					$serializer->booleanPropertyHasValue($booleanProperty, $boolean);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"booleanProperty":false}'))

			->given(
				$boolean = new object\property\boolean(true)
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($booleanProperty, $boolean) {
					$serializer->booleanPropertyHasValue($booleanProperty, $boolean);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"booleanProperty":true}'))

			->given(
				$storableProperty = new object\property('storableProperty'),
				$otherStorable = new \mock\estvoyage\object\storable
			)
			->if(
				$this->calling($otherStorable)->storerIsReady = function($serializer) use ($integerProperty, $integer) {
					$serializer->integerPropertyHasValue($integerProperty, $integer);
				},
				$this->calling($storable)->storerIsReady = function($serializer) use ($storableProperty, $otherStorable) {
					$serializer->storablePropertyHasValue($storableProperty, $otherStorable);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"storableProperty":{"integerProperty":666}}'))

			->given(
				$arrayProperty = new object\property('arrayProperty')
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($arrayProperty, $otherStorable) {
					$serializer->arrayPropertyHasValues($arrayProperty, $otherStorable);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"arrayProperty":[{"integerProperty":666}]}'))

			->given(
				$nullProperty = new object\property('nullProperty')
			)
			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use ($nullProperty) {
					$serializer->nullProperty($nullProperty);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable))->isEqualTo(new serialization\serialization('{"nullProperty":null}'))

			->if(
				$this->calling($storable)->storerIsReady = function($serializer) use (
						$stringProperty, $string,
						$integerProperty, $integer,
						$floatProperty, $float,
						$booleanProperty, $boolean,
						$storableProperty, $otherStorable,
						$arrayProperty,
						$nullProperty
					) {
					$serializer
						->stringPropertyHasValue($stringProperty, $string)
						->integerPropertyHasValue($integerProperty, $integer)
						->floatPropertyHasValue($floatProperty, $float)
						->booleanPropertyHasValue($booleanProperty, $boolean)
						->storablePropertyHasValue($storableProperty, $otherStorable)
						->arrayPropertyHasValues($arrayProperty, $otherStorable)
						->nullProperty($nullProperty)
					;
				}
			)
			->then
				->object(
					$this->testedInstance->serialize($storable))->isEqualTo(
						new serialization\serialization(
							'{"stringProperty":"a string","integerProperty":666,"floatProperty":666.999,"booleanProperty":true,"storableProperty":{"integerProperty":666},"arrayProperty":[{"integerProperty":666}],"nullProperty":null}'
						)
					)
		;
	}
}
