# *estvoyage\serialization* [![Build Status](https://api.travis-ci.org/estvoyage/serialization.svg)](https://travis-ci.org/estvoyage/serialization) [![Coverage Status](https://coveralls.io/repos/estvoyage/serialization/badge.svg)](https://coveralls.io/r/estvoyage/serialization)

## An east-oriented serialization interface

**serialization** is a [PHP](http://www.php.net) library that allows you to implement a [serialization](http://en.wikipedia.org/wiki/Serialization) protocol in any class.  
Traditionaly, if you want serialize an instance as JSON, you implements the [`jsonSerializable`](http://php.net/jsonserializable) interface in its class.  
Moreover, if several classes should support JSON serialization, you should implements this interface in each of them.  
And if you want using an another serialization format, as XML, you should implements a new method in each classes.  
With **serialization**, you can define only one method in each classes to handle any number of serialization format.

## Installation

Minimal PHP version to use **serialization** is 5.6.  
The recommended way to install Negotiation is through [Composer](http://getcomposer.org/), just add this in your `composer.json` and execute `php composer.phar install`:

``` json
{
	"require": {
		"estvoyage/serialization": "@dev",
		"estvoyage/object": "@dev",
		"estvoyage/value": "@dev"
	}
}
```

## Usage

In a nutshell:

```php
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

/* Output will be something like:
Serialization: <{"stringProperty":"5511d589ef90a","integerProperty":666,"floatProperty":666.999,"booleanProperty":true,"storableProperty":{"value":"5511d589ef932"},"nullProperty":null}>
Serialization: <stringProperty,integerProperty,floatProperty,booleanProperty,storableProperty.value,nullProperty
5511d589ef90a,666,666.999,1,5511d589ef932,
>
*/
```

A working script is available in the bundled `examples` directory, just do `php examples/nutshell.php` to execute it.

## Unit Tests

Setup the test suite using Composer:

    $ composer install --dev

Run it using **atoum**:

    $ vendor/bin/atoum

## Contributing

See the bundled `CONTRIBUTING` file for details.

## License

**serialization** is released under the FreeBSD License, see the bundled `COPYING` file for details.
