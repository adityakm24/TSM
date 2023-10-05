<?php

namespace rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers;

use rednaoeasycalculationforms\core\Managers\SerializationManager\Sanitizers\Core\SanitizerBase;
use rednaoeasycalculationforms\Utilities\Sanitizer;

class StringSanitizer extends SanitizerBase
{

    protected function InternalSerialize($originalObject, $newObject)
    {
        return Sanitizer::SanitizeString($originalObject->{$this->Property},$this->DefaultValue);
    }

}