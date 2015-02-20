<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\object,
	estvoyage\serialization
;

final class json extends serialization\serializer
{
	private
		$delimiter = '',
		$buffer = ''
	;

	protected function prepareSerialization()
	{
		return new self;
	}

	protected function serializeType(object\type $type)
	{
	}

	protected function serializeStringPropertyWithValue(object\property $property, object\property\string $string)
	{
		$this->serializeProperty($property, json_encode((string) $string));
	}

	protected function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer)
	{
		$this->serializeProperty($property, json_encode($integer->asInteger));
	}

	protected function serializeFloatPropertyWithValue(object\property $property, object\property\float $float)
	{
		$this->serializeProperty($property, json_encode($float->asFloat));
	}

	protected function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean)
	{
		$this->serializeProperty($property, json_encode($boolean->value));
	}

	protected function serializeStorablePropertyWithValue(object\property $property, object\storable $storable)
	{
		$this->serializeProperty($property, $this->serialize($storable));
	}

	protected function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$serialization = [];

		array_unshift($storables, $storable);

		foreach ($storables as $storable)
		{
			$serialization[] = $this->serialize($storable);
		}

		$this->serializeProperty($property, '[' . join(',', $serialization) . ']');
	}

	protected function serializeNullProperty(object\property $property)
	{
		$this->serializeProperty($property, json_encode(null));
	}

	protected function finalizeSerialization()
	{
		return $this->serializationIs(new serialization\serialization('{' . $this->buffer . '}'));
	}

	private function serializeProperty($property, $json)
	{
		$this->buffer .= $this->delimiter . json_encode((string) $property) . ':' . $json;

		if (! $this->delimiter)
		{
			$this->delimiter = ',';
		}
	}
}
