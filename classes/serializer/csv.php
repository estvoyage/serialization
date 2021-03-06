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
		$header,
		$line,
		$namespace
	;

	function __construct(\estvoyage\csv\generator $csvGenerator = null)
	{
		$this->csvGenerator = $csvGenerator ?: new \estvoyage\csv\generator\rfc4180;
		$this->header = new \estvoyage\csv\record\line;
		$this->line = new \estvoyage\csv\record\line;
		$this->namespace = [];
	}

	function csvGeneratorIs(\estvoyage\csv\generator $csvGenerator)
	{
		return new self($csvGenerator);
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		$this->csvGenerator
			->newCsvRecord($this->header)
				->newCsvRecord($this->line)
					->dataConsumerIs($dataConsumer)
		;

		return $this;
	}

	function newStorable(object\storable $storable)
	{
		return (new self($this->csvGenerator))->storableIs($storable);
	}

	function objectTypeIs(object\type $type)
	{
		return $this;
	}

	function stringObjectPropertyHasValue(object\property $property, object\property\string $string)
	{
		return $this->newPropertyWithData($property, new data\data((string) $string));
	}

	function integerObjectPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		return $this->newPropertyWithData($property, new data\data((string) $integer));
	}

	function floatObjectPropertyHasValue(object\property $property, object\property\float $float)
	{
		return $this->newPropertyWithData($property, new data\data((string) $float));
	}

	function booleanObjectPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		return $this->newPropertyWithData($property, new data\data($boolean->value ? '1' : '0'));
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
		return $this->newPropertyWithData($property, new data\data(''));
	}

	private function storableIs(object\storable $storable)
	{
		$storable->objectStorerIs($this);

		return $this;
	}

	private function newPropertyWithData(object\property $property, data\data $value)
	{
		$this->header = $this->header->newData(new data\data((! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property));
		$this->line = $this->line->newData($value);

		return $this;
	}
}
