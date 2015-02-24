<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\serialization,
	estvoyage\data,
	estvoyage\object
;

final class csv extends generic implements \estvoyage\csv\record\provider
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

	function dataConsumerNeedSerializationOfStorable(data\consumer $dataConsumer, object\storable $storable)
	{
		$this
			->serialize($storable)
			->sendDataToConsumer($dataConsumer)
		;

		return $this;
	}

	function useCsvGenerator(\estvoyage\csv\generator $generator)
	{
		if ($this->header)
		{
			$generator
				->newCsvRecords(
					new \estvoyage\csv\record(...$this->header),
					new \estvoyage\csv\record(...$this->line)
				)
			;
		}

		return $this;
	}

	protected function init()
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

	private function sendDataToConsumer(data\consumer $dataConsumer)
	{
		! $this->header
			?
			$dataConsumer->newData(new data\data(''))
			:
			$this->csvGenerator->forwardRecordFromProviderToDataConsumer($this, $dataConsumer)
		;
	}

	private function addPropertyWithValue(object\property $property, $value)
	{
		$this->header[] = new data\data((! $this->namespace ? '' : join('.', $this->namespace) . '.') . $property);
		$this->line[] = new data\data((string) $value);
	}
}
