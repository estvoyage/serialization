<?php

namespace estvoyage\serialization;

use
	estvoyage\data,
	estvoyage\object
;

interface serializer extends object\storer
{
	function dataConsumerNeedSerializationOfStorable(data\consumer $dataConsumer, object\storable $storable);
}
