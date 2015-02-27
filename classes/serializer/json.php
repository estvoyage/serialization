<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\data,
	estvoyage\object
;

final class json extends generic
{
	private
		$delimiter = '',
		$buffer = '',
		$depth = 0
	;

	protected function serializerReadyToSerialize()
	{
		$serializer = new self;
		$serializer->depth = $this->depth + 1;

		return $serializer;
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
		$this->serializeProperty($property, $this->newStorable($storable)->buffer);
	}

	protected function serializeArrayPropertyWithValues(object\property $property, object\storable $firstStorable, object\storable... $storables)
	{
		array_unshift($storables, $firstStorable);

		$json = [];

		foreach ($storables as $storable)
		{
			$json[] = $this->newStorable($storable)->buffer;
		}

		$this->serializeProperty($property, '[' . join(',', $json) . ']');
	}

	protected function serializeNullProperty(object\property $property)
	{
		$this->serializeProperty($property, json_encode(null));
	}

	protected function dataConsumerIs(data\consumer $dataConsumer)
	{
		$this->buffer = '{' . $this->buffer . '}';

		if ($this->depth == 1)
		{
			$dataConsumer->newData(new data\data($this->buffer));
		}
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
