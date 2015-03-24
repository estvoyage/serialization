<?php

namespace estvoyage\serialization\serializer;

use
	estvoyage\data,
	estvoyage\object,
	estvoyage\serialization
;

final class json implements serialization\serializer, data\provider
{
	private
		$data,
		$propertyDelimiter
	;

	private static
		$delimiter,
		$arrayStart,
		$arrayEnd
	;

	function __construct()
	{
		$this->data = new data\data;
	}

	function dataConsumerIs(data\consumer $dataConsumer)
	{
		$dataConsumer->newData(self::newJsonFromData($this->data));

		return $this;
	}

	function newStorable(object\storable $storable)
	{
		return (new self)->storableIs($storable);
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
				->newDataFromStorable($storable)
		;
	}

	function arrayObjectPropertyHasValues(object\property $property, object\storable $storable, object\storable... $storables)
	{
		$this
			->newProperty($property)
				->newData(self::arrayStart())
					->newDataFromStorable($storable)
		;

		foreach ($storables as $storable)
		{
			$this
				->newData(self::delimiter())
					->newDataFromStorable($storable)
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

	private function storableIs(object\storable $storable)
	{
		$storable->objectStorerIs($this);

		return $this;
	}

	private function newData(data\data $data)
	{
		$this->data = $this->data->newData($data);

		return $this;
	}

	private function newProperty(object\property $property)
	{
		return $this
			->propertyDelimiter()
				->newData(new data\data(json_encode((string) $property) . ':'))
		;
	}

	private function newDataFromStorable(object\storable $storable)
	{
		return $this->newData(self::newJsonFromData($this->newStorable($storable)->data));
	}

	private function propertyDelimiter()
	{
		! $this->propertyDelimiter
			?
			$this->propertyDelimiter = self::delimiter()
			:
			$this->newData($this->propertyDelimiter)
		;

		return $this;
	}

	private static function newJsonFromData(data\data $data)
	{
		return new data\data('{' . $data . '}');
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
