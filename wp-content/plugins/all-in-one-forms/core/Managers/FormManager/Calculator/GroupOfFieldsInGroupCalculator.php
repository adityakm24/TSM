<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;

class GroupOfFieldsInGroupCalculator extends CalculatorBase
{

    public function ExecutedCalculation($value)
    {
        $regularPrice=0;
        /** @var ContainerDataRetriever $field */
        $repeater=$this->Field;
        $unitPrice=0;
        $quantity=0;


        foreach($repeater->GetContainerManager()->GetFields(false,false,false) as $field)
        {
            if(!$field->Calculator->GetDependsOnOtherFields())
                $field->Calculator->ExecuteAndUpdate();
        }


        foreach($repeater->GetContainerManager()->GetFields(false,false,false) as $field)
        {
            if($field->Calculator->GetDependsOnOtherFields())
                $field->Calculator->ExecuteAndUpdate();
        }


        foreach($repeater->GetContainerManager()->GetFields(false,false,false) as $field)
        {
            $unitPrice+=$field->Calculator->GetPrice()*$field->Calculator->GetQuantity();
        }

        foreach($repeater->GetContainerManager()->GetFields(false,false,false) as $field)
        {
            $quantity+=$field->Calculator->GetQuantity();
        }




        return $this->CreateCalculationObject($unitPrice,'',1);
    }
}