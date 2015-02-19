<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\serialization,
	estvoyage\object
;

final class csv extends serialization\serializer
{
	private
		$header,
		$line,
		$namespace
	;

	protected function start()
	{
		$this->header = [];
		$this->line = [];

		return $this;
	}

	protected function serializeType(object\type $type)
	{
	}

	protected function serializeStringPropertyWithValue(object\property $property, object\property\string $string)
	{
		$this->addInLine($property, '"' . $string . '"');
	}

	protected function serializeIntegerPropertyWithValue(object\property $property, object\property\integer $integer)
	{
		$this->addInLine($property, $integer);
	}

	protected function serializeFloatPropertyWithValue(object\property $property, object\property\float $float)
	{
		$this->addInLine($property, $float);
	}

	protected function serializeBooleanPropertyWithValue(object\property $property, object\property\boolean $boolean)
	{
		$this->addInLine($property, $boolean->value ? 1 : 0);
	}

	protected function serializeStorablePropertyWithValue(object\property $property, object\storable $storable)
	{
		$serializer = clone $this;
		$serializer->namespace .= $property . '.';

		$storable->storerIsReady($serializer->start());

		$this->header = array_merge($this->header, $serializer->header);
		$this->line = array_merge($this->line, $serializer->line);
	}

	protected function serializeNullProperty(object\property $property)
	{
		$this->addInLine($property, '');
	}

	protected function serializeArrayPropertyWithValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		throw new csv\exception('Unable to serialize this kind of data');
	}

	protected function end()
	{

		$this
			->newSerialization(
				new serialization\serialization(
					! $this->header ? ''  : join(',', $this->header) . PHP_EOL . join(',', $this->line)
				)
			)
		;
	}

	private function addInLine(object\property $property, $value)
	{
		$property = '"' . $this->namespace . $property . '"';

		$key = array_search($property, $this->header);

		if ($key === false)
		{
			$key = sizeof($this->header);
			$this->header[$key] = $property;
		}

		$this->line[$key] = $value;
	}
}
