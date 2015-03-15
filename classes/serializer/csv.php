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
		$dataConsumer,
		$csvGenerator,
		$header,
		$line,
		$namespace = []
	;

	function __construct(data\consumer $dataConsumer = null, \estvoyage\csv\generator $csvGenerator = null)
	{
		$this->dataConsumer = $dataConsumer ?: new data\consumer\blackhole;
		$this->csvGenerator =
			$csvGenerator
			?
			$csvGenerator->dataConsumerIs($this->dataConsumer)
			:
			new \estvoyage\csv\generator\rfc4180($this->dataConsumer)
		;
		$this->header = new \estvoyage\csv\record\line;
		$this->line = new \estvoyage\csv\record\line;
	}

	function csvGeneratorIs(\estvoyage\csv\generator $csvGenerator)
	{
		return new self($this->dataConsumer, $csvGenerator);
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		return new self($dataConsumer, $this->csvGenerator);
	}

	function newStorable(object\storable $storable)
	{
		$serializer = clone $this;

		$storable->objectStorerIs($serializer);

		$serializer->noMoreObjectProperty();

		return $this;
	}

	function objectTypeIs(object\type $type)
	{
		return $this;
	}

	function stringObjectPropertyHasValue(object\property $property, object\property\string $string)
	{
		return $this->addPropertyWithValue($property, new data\data((string) $string));
	}

	function integerObjectPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		return $this->addPropertyWithValue($property, new data\data((string) $integer));
	}

	function floatObjectPropertyHasValue(object\property $property, object\property\float $float)
	{
		return $this->addPropertyWithValue($property, new data\data((string) $float));
	}

	function booleanObjectPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		return $this->addPropertyWithValue($property, new data\data($boolean->value ? '1' : '0'));
	}

	function storableObjectPropertyHasValue(object\property $property, object\storable $storable)
	{
		$this->namespace[] = $property;

		$storable->objectStorerIs($this);

		array_pop($this->namespace);

		return $this;
	}

	function arrayObjectPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		throw new csv\exception('Unable to serialize this kind of data');
	}

	function nullObjectProperty(object\property $property)
	{
		return $this->addPropertyWithValue($property, new data\data(''));
	}

	function noMoreObjectProperty()
	{
		$this->csvGenerator->newCsvRecord($this->header);
		$this->csvGenerator->newCsvRecord($this->line);

		return $this;
	}

	private function addPropertyWithValue(object\property $property, data\data $value)
	{
		$this->header = $this->header->newData(new data\data((! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property));
		$this->line = $this->line->newData($value);

		return $this;
	}
}
