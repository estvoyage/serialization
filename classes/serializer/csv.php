<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\data,
	estvoyage\object,
	estvoyage\serialization
;

final class csv implements serialization\serializer, data\provider
{
	private
		$csvGenerator,
		$header = [],
		$line = [],
		$namespace = []
	;

	function __construct(data\consumer $dataConsumer, \estvoyage\csv\generator $csvGenerator)
	{
		$this->csvGenerator = $csvGenerator->dataConsumerIs($dataConsumer);
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		return new self($dataConsumer, $this->csvGenerator);
	}

	function newStorable(object\storable $storable)
	{
		$storable->objectStorerIs($this);

		if (! $this->namespace)
		{
			$this->csvGenerator->newCsvRecord(new \estvoyage\csv\record\line(...$this->header));
			$this->csvGenerator->newCsvRecord(new \estvoyage\csv\record\line(...$this->line));

			$this->header = [];
			$this->line = [];
		}

		return $this;
	}

	function objectTypeIs(object\type $type)
	{
		return $this;
	}

	function stringObjectPropertyHasValue(object\property $property, object\property\string $string)
	{
		return $this->addPropertyWithValue($property, $string);
	}

	function integerObjectPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		return $this->addPropertyWithValue($property, $integer);
	}

	function floatObjectPropertyHasValue(object\property $property, object\property\float $float)
	{
		return $this->addPropertyWithValue($property, $float);
	}

	function booleanObjectPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		return $this->addPropertyWithValue($property, $boolean->value ? 1 : 0);
	}

	function storableObjectPropertyHasValue(object\property $property, object\storable $storable)
	{
		$this->namespace[] = $property;

		$this->newStorable($storable);

		array_pop($this->namespace);

		return $this;
	}

	function arrayObjectPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		throw new csv\exception('Unable to serialize this kind of data');
	}

	function nullObjectProperty(object\property $property)
	{
		return $this->addPropertyWithValue($property, '');
	}

	private function addPropertyWithValue(object\property $property, $value)
	{
		$this->header[] = new data\data((! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property);
		$this->line[] = new data\data((string) $value);

		return $this;
	}
}
