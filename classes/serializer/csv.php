<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\serialization,
	estvoyage\data,
	estvoyage\object
;

final class csv extends generic
{
	private
		$header = [],
		$line = [],
		$namespace = []
	;

	protected function init()
	{
		$serializer = new self;

		if ($this->namespace)
		{
			$serializer->header = & $this->header;
			$serializer->line = & $this->line;
			$serializer->namespace = & $this->namespace;
		}

		return $serializer;
	}

	protected function serializeType(object\type $type)
	{
	}

	protected function serializeStringPropertyWithValue(object\property $property, object\property\string $string)
	{
		$this->addPropertyWithValue($property, '"' . $string . '"');
	}

	protected function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer)
	{
		$this->addPropertyWithValue($property, $integer);
	}

	protected function serializeFloatPropertyWithValue(object\property $property, object\property\float $float)
	{
		$this->addPropertyWithValue($property, $float);
	}

	protected function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean)
	{
		$this->addPropertyWithValue($property, $boolean->value ? 1 : 0);
	}

	protected function serializeStorablePropertyWithValue(object\property $property, object\storable $storable)
	{
		$this->namespace[] = $property;

		$this->serialize($storable);

		array_pop($this->namespace);
	}

	protected function serializeNullProperty(object\property $property)
	{
		$this->addPropertyWithValue($property, '');
	}

	protected function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		throw new csv\exception('Unable to serialize this kind of data');
	}

	protected function buildData()
	{
		$data = '';

		if (! $this->namespace && $this->header)
		{
			$data = join(',', $this->header) . PHP_EOL . join(',', $this->line);
		}

		return new data\data($data);
	}

	private function addPropertyWithValue(object\property $property, $value)
	{
		$property = '"' . (! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property . '"';

		$key = array_search($property, $this->header);

		if ($key === false)
		{
			$key = sizeof($this->header);
			$this->header[$key] = $property;
		}

		$this->line[$key] = $value;
	}
}
