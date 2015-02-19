<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\serialization,
	estvoyage\object,
	mock\estvoyage\object\storable
;

class csv extends units\test
{
	function testClass()
	{
		$this->testedClass
			->isFinal
			->extends('estvoyage\serialization\serializer')
		;
	}

	function testSerialize()
	{
		$this
			->given(
				$this->newTestedInstance,
				$storable1 = new storable
			)

			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) {}
			)
			->then
				->object($this->testedInstance->serialize($storable1))->isEqualTo(new serialization\serialization)

			->if(
				$type = new object\type('aType'),
				$this->calling($storable1)->storerIsReady = function($serializer) use ($type) {
					$serializer->typeIs($type);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))->isEqualTo(new serialization\serialization)

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
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"string1Property"' . PHP_EOL . '"a string 1"'
						)
					)

			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
					$serializer->stringPropertyHasValue($string1Property, $string1);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"string1Property"' . PHP_EOL . '"a string 1"'
						)
					)

			->given(
				$string2Property = new object\property('string2Property'),
				$string2 = new object\property\string('a string 2')
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($string1Property, $string1, $string2Property, $string2) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
					$serializer->stringPropertyHasValue($string2Property, $string2);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"string1Property","string2Property"' . PHP_EOL .
							'"a string 1","a string 2"'
						)
					)

			->given(
				$storable1Property = new object\property('storable1Property'),
				$storable2 = new \mock\estvoyage\object\storable
			)

			->if(
				$this->calling($storable2)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->storerIsReady = function($serializer) use ($storable1Property, $storable2) {
					$serializer->storablePropertyHasValue($storable1Property, $storable2);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"storable1Property.string1Property"' . PHP_EOL .
							'"a string 1"'
						)
					)

			->if(
				$this->calling($storable2)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->storerIsReady = function($serializer) use ($string1Property, $string1, $storable1Property, $storable2) {
					$serializer
						->stringPropertyHasValue($string1Property, $string1)
						->storablePropertyHasValue($storable1Property, $storable2)
					;
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(new serialization\serialization(
							'"string1Property","storable1Property.string1Property"' . PHP_EOL .
							'"a string 1","a string 1"'
						)
					)

			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use (
						$string1Property, $string1,
						$storable1Property, $storable2,
						$string2Property, $string2
					) {
					$serializer
						->stringPropertyHasValue($string1Property, $string1)
						->storablePropertyHasValue($storable1Property, $storable2)
						->stringPropertyHasValue($string2Property, $string2)
					;
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(new serialization\serialization(
							'"string1Property","storable1Property.string1Property","string2Property"' . PHP_EOL .
							'"a string 1","a string 1","a string 2"'
						)
					)

			->given(
				$arrayProperty = new object\property('arrayProperty'),
				$storable2 = new \mock\estvoyage\object\storable
			)

			->if(
				$this->calling($storable2)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
				},
				$this->calling($storable1)->storerIsReady = function($serializer) use ($arrayProperty, $storable2) {
					$serializer->arrayPropertyHasValues($arrayProperty, $storable2);
				}
			)
			->then
				->exception(function() use ($storable1) { $this->testedInstance->serialize($storable1); })
					->isInstanceOf('estvoyage\serialization\serializer\csv\exception')
					->hasMessage('Unable to serialize this kind of data')

			->given(
				$integer1Property = new object\property('integer1Property'),
				$integer1 = new object\property\integer(666)
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use (
						$integer1Property, $integer1
					) {
					$serializer->integerPropertyHasValue($integer1Property, $integer1);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"integer1Property"' . PHP_EOL .
							'666'
						)
					)

			->given(
				$float1Property = new object\property('float1Property'),
				$float1 = new object\property\float(666.999)
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use (
						$float1Property, $float1
					) {
					$serializer->floatPropertyHasValue($float1Property, $float1);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"float1Property"' . PHP_EOL .
							'666.999'
						)
					)

			->given(
				$boolean1Property = new object\property('boolean1Property'),
				$boolean1 = new object\property\boolean(false)
			)
			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use (
						$boolean1Property, $boolean1
					) {
					$serializer->booleanPropertyHasValue($boolean1Property, $boolean1);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable1))
					->isEqualTo(
						new serialization\serialization(
							'"boolean1Property"' . PHP_EOL .
							'0'
						)
					)

			->given(
				$boolean2Property = new object\property('boolean2Property'),
				$boolean2 = new object\property\boolean(true)
			)
			->if(
				$this->calling($storable2)->storerIsReady = function($serializer) use (
						$boolean2Property, $boolean2
					) {
					$serializer->booleanPropertyHasValue($boolean2Property, $boolean2);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable2))
					->isEqualTo(
						new serialization\serialization(
							'"boolean2Property"' . PHP_EOL .
							'1'
						)
					)

			->given(
				$nullProperty = new object\property('nullProperty')
			)
			->if(
				$this->calling($storable2)->storerIsReady = function($serializer) use (
						$nullProperty
					) {
					$serializer->nullProperty($nullProperty);
				}
			)
			->then
				->object($this->testedInstance->serialize($storable2))
					->isEqualTo(
						new serialization\serialization(
							'"nullProperty"' . PHP_EOL .
							''
						)
					)
		;
	}
}
