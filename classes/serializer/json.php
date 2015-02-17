<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\object,
	estvoyage\serialization
;

final class json extends serialization\serializer
{
	private
		$delimiter = ''
	;

	function serialize(object\storable $storable)
	{
		$serialization = new serialization\serialization('{' . parent::serialize($storable) . '}');

		$this->delimiter = '';

		return $serialization;
	}

	function serializeType(object\type $type)
	{
		return $this;
	}

	function serializeStringProperyWithValue(object\property $property, object\property\string $string)
	{
		return $this->serializeProperty($property, json_encode((string) $string));
	}

	function serializeIntegerProperyWithValue(object\property $property, object\property\integer $integer)
	{
		return $this->serializeProperty($property, json_encode($integer->asInteger));
	}

	function serializeFloatPropertyWithValue(object\property $property, object\property\float $float)
	{
		return $this->serializeProperty($property, json_encode($float->asFloat));
	}

	function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean)
	{
		return $this->serializeProperty($property, json_encode($boolean->value));
	}

	function serializeStorablePropertyWithValue(object\property $property, object\storable $storable)
	{
		return $this->serializeProperty($property, (new self)->serialize($storable));
	}

	function serializeNullProperty(object\property $property)
	{
		return $this->serializeProperty($property, json_encode(null));
	}

	private function serializeProperty($property, $json)
	{
		$this->newSerialization(new serialization\serialization($this->delimiter . json_encode((string) $property) . ':' . $json));

		if (! $this->delimiter)
		{
			$this->delimiter = ',';
		}

		return $this;
	}
}
