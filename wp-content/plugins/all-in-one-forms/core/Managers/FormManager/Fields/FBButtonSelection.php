<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\ConditionManager\Comparator\SingleValueComparator;

class FBButtonSelection extends FBMultipleOptionsField
{
    protected function CanSanitize()
    {
        return true;
    }

    protected function InternalSanitize($sanitizer)
    {
        parent::InternalSanitize($sanitizer); // TODO: Change the autogenerated stub
        $item=$sanitizer->GetSanitizerByProperty('SelectedValues');
        if($item!=null)
            $item->Manager->AddIgnoreSanitizer("Icon",(Object)[],true);
    }
}