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
		$namespace = [],
		$csvGenerator
	;

	function __construct(\estvoyage\csv\generator $csvGenerator = null)
	{
		$this->csvGenerator = $csvGenerator;
	}

	protected function serializerReadyToSerialize()
	{
		return $this->namespace ? $this : new self($this->csvGenerator);
	}

	protected function serializeType(object\type $type)
	{
	}

	protected function serializeStringPropertyWithValue(object\property $property, object\property\string $string)
	{
		$this->addPropertyWithValue($property, $string);
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

		$this->newStorable($storable);

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

	protected function dataConsumerIs(data\consumer $dataConsumer)
	{
		if (! $this->namespace && $this->header)
		{
			$this->csvGenerator
				->dataConsumerNeedCsvRecord($dataConsumer, new \estvoyage\csv\record\line(...$this->header))
				->dataConsumerNeedCsvRecord($dataConsumer, new \estvoyage\csv\record\line(...$this->line))
			;
		}

		return $this;
	}

	private function addPropertyWithValue(object\property $property, $value)
	{
		$this->header[] = new data\data((! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property);
		$this->line[] = new data\data((string) $value);
	}
}
