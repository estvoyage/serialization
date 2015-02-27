<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\data,
	estvoyage\object,
	estvoyage\serialization,
	estvoyage\serialization\exception
;

abstract class generic implements serialization\serializer
{
	private
		$dataConsumer
	;

	final function dataConsumerNeedSerializationOfStorable(data\consumer $dataConsumer, object\storable $storable)
	{
		$this
			->serializerForDataConsumerIs($dataConsumer, $this->serializerReadyToSerialize())
				->storableIs($storable)
					->dataConsumerIs($dataConsumer)
		;

		return $this;
	}

	final function typeIs(object\type $type)
	{
		$this
			->ifDataConsumer()
				->serializeType($type)
		;

		return $this;
	}

	final function stringPropertyHasValue(object\property $property, object\property\string $string)
	{
		$this
			->ifDataConsumer()
				->serializeStringPropertyWithValue($property, $string)
		;

		return $this;
	}

	final function integerPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		$this
			->ifDataConsumer()
				->serializeIntegerPropertyWithValue($property, $integer)
		;

		return $this;
	}

	final function floatPropertyHasValue(object\property $property, object\property\float $float)
	{
		$this
			->ifDataConsumer()
				->serializeFloatPropertyWithValue($property, $float)
		;

		return $this;
	}

	final function booleanPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		$this
			->ifDataConsumer()
				->serializeBooleanPropertyWithValue($property, $boolean)
		;

		return $this;
	}

	final function storablePropertyHasValue(object\property $property, object\storable $storable)
	{
		$this
			->ifDataConsumer()
				->serializeStorablePropertyWithValue($property, $storable)
		;

		return $this;
	}

	final function arrayPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->ifDataConsumer()
				->serializeArrayPropertyWithValues($property, $storable, ...$storables)
		;

		return $this;
	}

	final function nullProperty(object\property $property)
	{
		$this
			->ifDataConsumer()
				->serializeNullProperty($property)
		;

		return $this;
	}

	final protected function serializerForDataConsumerIs(data\consumer $dataConsumer, self $serializer)
	{
		$serializer->dataConsumer = $dataConsumer;

		return $serializer;
	}

	final protected function ifDataConsumer()
	{
		if (! $this->dataConsumer)
		{
			throw new exception\logic('Data consumer is undefined');
		}

		return $this;
	}

	final protected function newStorable(object\storable $storable)
	{
		$serializer = $this->serializerForDataConsumerIs($this->dataConsumer, $this->serializerReadyToSerialize());

		$serializer
				->storableIs($storable)
				->dataConsumerIs($this->dataConsumer)
		;

		return $serializer;
	}

	protected abstract function serializeType(object\type $type);
	protected abstract function serializeStringPropertyWithValue(object\property $property, object\property\string $string);
	protected abstract function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer);
	protected abstract function serializeFloatPropertyWithValue(object\property $property, object\property\float $float);
	protected abstract function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean);
	protected abstract function serializeStorablePropertyWithValue(object\property $property, object\storable $storable);
	protected abstract function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables);
	protected abstract function serializeNullProperty(object\property $property);
	protected abstract function dataConsumerIs(data\consumer $dataConsumer);

	private function storableIs(object\storable $storable)
	{
		$storable->storerIsReady($this);

		return $this;
	}
}
