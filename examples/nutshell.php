<?php

require __DIR__ . '/../vendor/autoload.php';

use
	estvoyage\csv,
	estvoyage\data,
	estvoyage\object,
	estvoyage\serialization
;

class console implements data\consumer
{
	function newData(data\data $data)
	{
		echo $data . PHP_EOL;

		return $this;
	}
}

class foo implements object\storable
{
	private
		$stringProperty,
		$integerProperty,
		$floatProperty,
		$booleanProperty,
		$storableProperty,
		$nullProperty
	;

	function __construct($string, $integer, $float, $boolean, object\storable $storable, $null = null)
	{
		$this->stringProperty = $string;
		$this->integerProperty = $integer;
		$this->floatProperty = $float;
		$this->booleanProperty = $boolean;
		$this->storableProperty = $storable;
		$this->nullProperty = $null;
	}

	function storerIsReady(object\storer $storer)
	{
		$storer
			->stringPropertyHasValue(new object\property('stringProperty'), new object\property\string($this->stringProperty))
			->integerPropertyHasValue(new object\property('integerProperty'), new object\property\integer($this->integerProperty))
			->floatPropertyHasValue(new object\property('floatProperty'), new object\property\float($this->floatProperty))
			->booleanPropertyHasValue(new object\property('booleanProperty'), new object\property\boolean($this->booleanProperty))
			->storablePropertyHasValue(new object\property('storableProperty'), $this->storableProperty)
			->nullProperty(new object\property('nullProperty'))
		;
	}
}

class bar implements object\storable
{
	private
		$value
	;

	function __construct($value)
	{
		$this->value = $value;
	}

	function storerIsReady(object\storer $storer)
	{
		$storer
			->stringPropertyHasValue(new object\property('value'), new object\property\string($this->value))
		;
	}
}

$console = new console;
$foo = new foo(uniqid(), 666, 666.999, true, new bar(uniqid()));

(new serialization\serializer\json)
	->dataConsumerNeedSerializationOfStorable(
		$console->newData(new data\data('As JSON:')),
		$foo
	)
;

(new serialization\serializer\csv(new csv\generator\rfc4180))
	->dataConsumerNeedSerializationOfStorable(
		$console->newData(new data\data('As CSV:')),
		$foo
	)
;
