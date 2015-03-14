<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\data,
	estvoyage\object,
	estvoyage\serialization
;

final class json  implements serialization\serializer, data\provider
{
	private
		$dataConsumer,
		$currentDelimiter
	;

	private static
		$delimiter,
		$objectStart,
		$objectEnd,
		$arrayStart,
		$arrayEnd
	;

	function __construct(data\consumer $dataConsumer = null)
	{
		$this->dataConsumer = $dataConsumer ?: new data\consumer\blackhole;
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		return new self($dataConsumer);
	}

	function newStorable(object\storable $storable)
	{
		(new self($this->dataConsumer))
			->newData(self::$objectStart ?: (self::$objectStart = new data\data('{')))
			->storableIs($storable)
			->newData(self::$objectEnd ?: (self::$objectEnd = new data\data('}')))
		;

		return $this;
	}

	function objectTypeIs(object\type $type)
	{
		return $this;
	}

	function stringObjectPropertyHasValue(object\property $property, object\property\string $string)
	{
		return $this
			->newProperty($property)
			->newData(new data\data(json_encode((string) $string)))
		;
	}

	function integerObjectPropertyHasValue(object\property $property, object\property\integer $integer)
	{
		return $this
			->newProperty($property)
			->newData(new data\data(json_encode($integer->asInteger)))
		;
	}

	function floatObjectPropertyHasValue(object\property $property, object\property\float $float)
	{
		return $this
			->newProperty($property)
			->newData(new data\data((string) $float))
		;
	}

	function booleanObjectPropertyHasValue(object\property $property, object\property\boolean $boolean)
	{
		return $this
			->newProperty($property)
			->newData(new data\data(json_encode($boolean->value)))
		;
	}

	function storableObjectPropertyHasValue(object\property $property, object\storable $storable)
	{
		return $this
			->newProperty($property)
			->newStorable($storable)
		;
	}

	function arrayObjectPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->newProperty($property)
			->newData(self::arrayStart())
			->newStorable($storable)
		;

		foreach ($storables as $storable)
		{
			$this
				->newData(self::delimiter())
				->newStorable($storable)
			;
		}

		return $this->newData(self::arrayEnd());
	}

	function nullObjectProperty(object\property $property)
	{
		return $this
			->newProperty($property)
			->newData(new data\data(json_encode(null)))
		;
	}

	private function newProperty(object\property $property)
	{
		return $this->newData(new data\data($this->currentDelimiter() . json_encode((string) $property) . ':'));
	}

	private function newData(data\data $data)
	{
		$this->dataConsumer->newData($data);

		return $this;
	}

	private function storableIs(object\storable $storable)
	{
		$storable->objectStorerIs($this);

		return $this;
	}

	private function currentDelimiter()
	{
		$delimiter = $this->currentDelimiter;

		if (! $this->currentDelimiter)
		{
			$this->currentDelimiter = self::delimiter();
		}

		return $delimiter;
	}

	private static function delimiter()
	{
		return self::$delimiter ?: (self::$delimiter = new data\data(','));
	}

	private static function arrayStart()
	{
		return self::$arrayStart ?: (self::$arrayStart = new data\data('['));
	}

	private static function arrayEnd()
	{
		return self::$arrayEnd ?: (self::$arrayEnd = new data\data(']'));
	}
}
