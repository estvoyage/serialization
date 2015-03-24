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
	function dataProviderIs(data\provider $provider)
	{
		$provider->dataConsumerIs($this);

		return $this;
	}

	function newData(data\data $data)
	{
		echo 'Serialization: <' . $data . '>' . PHP_EOL;

		return $this;
	}

	function noMoreData()
	{
		return $this;
	}

	function dataConsumerControllerIs(data\consumer\controller $controller)
	{
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

	function objectStorerIs(object\storer $storer)
	{
		$storer
			->stringObjectPropertyHasValue(new object\property('stringProperty'), new object\property\string($this->stringProperty))
			->integerObjectPropertyHasValue(new object\property('integerProperty'), new object\property\integer($this->integerProperty))
			->floatObjectPropertyHasValue(new object\property('floatProperty'), new object\property\float($this->floatProperty))
			->booleanObjectPropertyHasValue(new object\property('booleanProperty'), new object\property\boolean($this->booleanProperty))
			->storableObjectPropertyHasValue(new object\property('storableProperty'), $this->storableProperty)
			->nullObjectProperty(new object\property('nullProperty'))
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

	function objectStorerIs(object\storer $storer)
	{
		$storer
			->stringObjectPropertyHasValue(new object\property('value'), new object\property\string($this->value))
		;
	}
}

$console = new console;

$foo = new foo(uniqid(), 666, 666.999, true, new bar(uniqid()));

(new serialization\serializer\json)
	->newStorable($foo)
	->dataConsumerIs($console)
;

(new serialization\serializer\csv(new csv\generator\rfc4180))
	->newStorable($foo)
	->dataConsumerIs($console)
;
