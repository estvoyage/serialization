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

	protected function start()
	{
		$this->delimiter = '';

		$this->newSerialization(new serialization\serialization('{'));
	}

	protected function serializeType(object\type $type)
	{
		return $this;
	}

	protected function serializeStringPropertyWithValue(object\property $property, object\property\string $string)
	{
		return $this->serializeProperty($property, json_encode((string) $string));
	}

	protected function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer)
	{
		return $this->serializeProperty($property, json_encode($integer->asInteger));
	}

	protected function serializeFloatPropertyWithValue(object\property $property, object\property\float $float)
	{
		return $this->serializeProperty($property, json_encode($float->asFloat));
	}

	protected function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean)
	{
		return $this->serializeProperty($property, json_encode($boolean->value));
	}

	protected function serializeStorablePropertyWithValue(object\property $property, object\storable $storable)
	{
		return $this->serializeProperty($property, $this->serialize($storable));
	}

	protected function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$serialization = [];

		array_unshift($storables, $storable);

		foreach ($storables as $storable)
		{
			$serialization[] = $this->serialize($storable);
		}

		return $this->serializeProperty($property, '[' . join(',', $serialization) . ']');
	}

	protected function serializeNullProperty(object\property $property)
	{
		return $this->serializeProperty($property, json_encode(null));
	}

	protected function end()
	{
		$this->newSerialization(new serialization\serialization('}'));
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
