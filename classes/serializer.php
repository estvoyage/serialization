<?php

namespace estvoyage\serialization;

use
	estvoyage\object
;

abstract class serializer implements object\storer
{
	private
		$serialization
	;

	function serialize(object\storable $storable)
	{
		return
			$this
				->prepareSerialization()
				->startSerializationOf($storable)
				->finalizeSerialization()
				->endOfSerialization()
		;
	}

	final function typeIs(object\type $type)
	{
		$this
			->ifReady()
				->serializeType($type)
		;

		return $this;
	}

	final function stringPropertyHasValue(object\property $property, object\property\string $string)
	{
		$this
			->ifReady()
				->serializeStringPropertyWithValue($property, $string)
		;

		return $this;
	}

	final function integerPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		$this
			->ifReady()
				->serializeIntegerPropertyWithValue($property, $integer)
		;

		return $this;
	}

	final function floatPropertyHasValue(object\property $property, object\property\float $float)
	{
		$this
			->ifReady()
				->serializeFloatPropertyWithValue($property, $float)
		;

		return $this;
	}

	final function booleanPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		$this
			->ifReady()
				->serializeBooleanPropertyWithValue($property, $boolean)
		;

		return $this;
	}

	final function storablePropertyHasValue(object\property $property, object\storable $storable)
	{
		$this
			->ifReady()
				->serializeStorablePropertyWithValue($property, $storable)
		;

		return $this;
	}

	final function arrayPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->ifReady()
				->serializeArrayPropertyWithValues($property, $storable, ...$storables)
		;

		return $this;
	}

	final function nullProperty(object\property $property)
	{
		$this
			->ifReady()
				->serializeNullProperty($property)
		;

		return $this;
	}

	protected abstract function prepareSerialization();
	protected abstract function serializeType(object\type $type);
	protected abstract function serializeStringPropertyWithValue(object\property $property, object\property\string $string);
	protected abstract function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer);
	protected abstract function serializeFloatPropertyWithValue(object\property $property, object\property\float $float);
	protected abstract function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean);
	protected abstract function serializeStorablePropertyWithValue(object\property $property, object\storable $storable);
	protected abstract function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables);
	protected abstract function serializeNullProperty(object\property $property);
	protected abstract function finalizeSerialization();

	final protected function serializationIs(serialization $serialization)
	{
		$this->serialization = $serialization;

		return $this;
	}

	private function startSerializationOf(object\storable $storable)
	{
		$this->serialization = new serialization;

		$storable->storerIsReady($this);

		return $this;
	}

	private function endOfSerialization()
	{
		$serialization = $this->serialization;

		$this->serialization = null;

		return $serialization;
	}

	private function ifReady()
	{
		if (! $this->serialization)
		{
			throw new exception\logic('Serializer is not ready');
		}

		return $this;
	}
}
