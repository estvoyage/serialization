<?php

namespace estvoyage\serialization;

use
	estvoyage\data,
	estvoyage\object
;

interface serializer extends object\storer
{
	function newStorable(object\storable $storable);
}
