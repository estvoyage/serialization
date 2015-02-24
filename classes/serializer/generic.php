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
		$serializer
	;

	final function typeIs(object\type $type)
	{
		$this
			->ifSerializationInProgress()
				->serializeType($type)
		;

		return $this;
	}

	final function stringPropertyHasValue(object\property $property, object\property\string $string)
	{
		$this
			->ifSerializationInProgress()
				->serializeStringPropertyWithValue($property, $string)
		;

		return $this;
	}

	final function integerPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		$this
			->ifSerializationInProgress()
				->serializeIntegerPropertyWithValue($property, $integer)
		;

		return $this;
	}

	final function floatPropertyHasValue(object\property $property, object\property\float $float)
	{
		$this
			->ifSerializationInProgress()
				->serializeFloatPropertyWithValue($property, $float)
		;

		return $this;
	}

	final function booleanPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		$this
			->ifSerializationInProgress()
				->serializeBooleanPropertyWithValue($property, $boolean)
		;

		return $this;
	}

	final function storablePropertyHasValue(object\property $property, object\storable $storable)
	{
		$this
			->ifSerializationInProgress()
				->serializeStorablePropertyWithValue($property, $storable)
		;

		return $this;
	}

	final function arrayPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->ifSerializationInProgress()
				->serializeArrayPropertyWithValues($property, $storable, ...$storables)
		;

		return $this;
	}

	final function nullProperty(object\property $property)
	{
		$this
			->ifSerializationInProgress()
				->serializeNullProperty($property)
		;

		return $this;
	}

	protected abstract function init();
	protected abstract function serializeType(object\type $type);
	protected abstract function serializeStringPropertyWithValue(object\property $property, object\property\string $string);
	protected abstract function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer);
	protected abstract function serializeFloatPropertyWithValue(object\property $property, object\property\float $float);
	protected abstract function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean);
	protected abstract function serializeStorablePropertyWithValue(object\property $property, object\storable $storable);
	protected abstract function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables);
	protected abstract function serializeNullProperty(object\property $property);

	final protected function serialize(object\storable $storable)
	{
		return
			$this
				->init()
					->setSerializer()
						->notifyStorable($storable)
		;
	}

	final protected function ifSerializationInProgress()
	{
		if (! $this->serializer)
		{
			throw new exception\logic('Serializer is not started');
		}

		return $this->serializer;
	}

	private function notifyStorable(object\storable $storable)
	{
		$storable->storerIsReady($this);

		return $this;
	}

	private function setSerializer()
	{
		$this->serializer = $this;

		return $this->serializer;
	}
}
