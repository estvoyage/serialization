<?php

namespace estvoyage\serialization\tests\units\serializer;

require __DIR__ . '/../../runner.php';

use
	estvoyage\serialization\tests\units,
	estvoyage\object,
	estvoyage\data,
	estvoyage\csv\record as csvRecord,
	mock\estvoyage\csv\generator as csvGenerator,
	mock\estvoyage\data\consumer,
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

	function testDataConsumerNeedSerializationOfStorable()
	{
		$this
			->given(
				$dataConsumer = new consumer,
				
				$this->calling($csvGenerator = new csvGenerator)->dataConsumerNeedCsvRecord = $csvGenerator,

				$this->newTestedInstance($csvGenerator)
			)

			->if(
				$this->calling($storable1 = new storable)->storerIsReady = function($serializer) {}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->never

			->if(
				$type = new object\type('aType'),
				$this->calling($storable1)->storerIsReady = function($serializer) use ($type) {
					$serializer->typeIs($type);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($dataConsumer)->receive('newData')->never

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('a string 1')))
							->once

			->if(
				$this->calling($storable1)->storerIsReady = function($serializer) use ($string1Property, $string1) {
					$serializer->stringPropertyHasValue($string1Property, $string1);
					$serializer->stringPropertyHasValue($string1Property, $string1);
				}
			)
			->then
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property'), new data\data('string1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('a string 1'), new data\data('a string 1')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property'), new data\data('string2Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property'), new data\data('string2Property')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('storable1Property.string1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('a string 1')))
							->twice

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property'), new data\data('storable1Property.string1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('a string 1'), new data\data('a string 1')))
							->twice

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('string1Property'), new data\data('storable1Property.string1Property'), new data\data('string2Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('a string 1'), new data\data('a string 1'), new data\data('a string 2')))
							->once

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
				->exception(function() use ($dataConsumer, $storable1) { $this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1); })
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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('integer1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('666')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('float1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('666.999')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable1))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('boolean1Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('0')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable2))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('boolean2Property')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('1')))
							->once

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
				->object($this->testedInstance->dataConsumerNeedSerializationOfStorable($dataConsumer, $storable2))->isTestedInstance
				->mock($csvGenerator)
					->receive('dataConsumerNeedCsvRecord')
						->withArguments($dataConsumer, new csvRecord\line(new data\data('nullProperty')))
							->once
						->withArguments($dataConsumer, new csvRecord\line(new data\data('')))
							->once
		;
	}
}
