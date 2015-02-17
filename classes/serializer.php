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
		$this->buffer = '';

		$storable->storerIsReady($this);

		$buffer = $this->buffer;

		$this->buffer = null;

		return new serialization($buffer);
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
			->serializeStringProperyWithValue($property, $string)
		;

		return $this;
	}

	final function integerPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		$this
			->checkIfReady()
			->serializeIntegerProperyWithValue($property, $integer)
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

	final function nullProperty(object\property $property)
	{
		$this
			->checkIfReady()
			->serializeNullProperty($property)
		;

		return $this;
	}

	protected abstract function serializeType(object\type $type);
	protected abstract function serializeStringProperyWithValue(object\property $property, object\property\string $string);
	protected abstract function serializeIntegerProperyWithValue(object\property $property, object\property\integer $integer);
	protected abstract function serializeFloatPropertyWithValue(object\property $property, object\property\float $float);
	protected abstract function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean);
	protected abstract function serializeStorablePropertyWithValue(object\property $property, object\storable $storable);
	protected abstract function serializeNullProperty(object\property $property);

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
