<?php

namespace estvoyage\serialization;

use
	estvoyage\data,
	estvoyage\object
;

interface serializer extends object\storer
{
	function dataConsumerNeedDataFromStorable(data\consumer $dataConsumer, object\storable $storable);
}
