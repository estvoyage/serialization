<?php

namespace estvoyage\serialization;

use
	estvoyage\object
;

abstract class serializer implements object\storer
{
	private
		$buffer
	;

	function serialize(object\storable $storable)
	{
		$serializer = clone $this;

		$serializer->buffer = '';

		$serializer->start();

		$storable->storerIsReady($serializer);

		$serializer->end();

		return new serialization($serializer->buffer);
	}

	final function typeIs(object\type $type)
	{
		$this
			->checkIfReady()
			->serializeType($type)
		;

		return $this;
	}

	final function stringPropertyHasValue(object\property $property, object\property\string $string)
	{
		$this
			->checkIfReady()
			->serializeStringPropertyWithValue($property, $string)
		;

		return $this;
	}

	final function integerPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		$this
			->checkIfReady()
			->serializeIntegerPropertyWithValue($property, $integer)
		;

		return $this;
	}

	final function floatPropertyHasValue(object\property $property, object\property\float $float)
	{
		$this
			->checkIfReady()
			->serializeFloatPropertyWithValue($property, $float)
		;

		return $this;
	}

	final function booleanPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		$this
			->checkIfReady()
			->serializeBooleanPropertyWithValue($property, $boolean)
		;

		return $this;
	}

	final function storablePropertyHasValue(object\property $property, object\storable $storable)
	{
		$this
			->checkIfReady()
			->serializeStorablePropertyWithValue($property, $storable)
		;

		return $this;
	}

	final function arrayPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->checkIfReady()
			->serializeArrayPropertyWithValues($property, $storable, ...$storables)
		;

		return $this;
	}

	final function nullProperty(object\property $property)
	{
		$this
			->checkIfReady()
			->serializeNullProperty($property)
		;

		return $this;
	}

	protected abstract function start();
	protected abstract function serializeType(object\type $type);
	protected abstract function serializeStringPropertyWithValue(object\property $property, object\property\string $string);
	protected abstract function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer);
	protected abstract function serializeFloatPropertyWithValue(object\property $property, object\property\float $float);
	protected abstract function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean);
	protected abstract function serializeStorablePropertyWithValue(object\property $property, object\storable $storable);
	protected abstract function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables);
	protected abstract function serializeNullProperty(object\property $property);
	protected abstract function end();

	final protected function newSerialization(serialization $serialization)
	{
		$this->buffer .= $serialization;

		return $this;
	}

	private function checkIfReady()
	{
		if ($this->buffer === null)
		{
			throw new exception\logic('Serializer is not ready');
		}

		return $this;
	}
}
