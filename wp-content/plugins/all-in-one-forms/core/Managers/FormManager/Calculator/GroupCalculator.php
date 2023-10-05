<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Calculator;


use rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager\ContainerDataRetriever;
use rednaoeasycalculationforms\core\Managers\FormManager\Fields\FBGroupPanel;
use rednaoeasycalculationforms\core\Utils\ArrayUtils;

class GroupCalculator extends CalculatorBase
{
    public $Total;
    /** @var FBGroupPanel */
    public $Field;
    public $OptionsUnitPrice;

    public function ExecutedCalculation($value)
    {
        if($this->Field->Entry==null)
            return $this->CreateCalculationObject('','',0);
        $this->OptionsUnitPrice=0;
        $this->Quantity=0;

        /** @var ContainerDataRetriever */
        $containerField = $this->Field;

        foreach ($containerField->GetContainerManager()->GetFields(false, false, false) as $field)
        {
            if (!$field->Calculator->GetDependsOnOtherFields())
                $field->Calculator->ExecuteAndUpdate();
        }


        foreach ($containerField->GetContainerManager()->GetFields(false, false, false) as $field)
        {
            $this->OptionsUnitPrice += $field->Calculator->GetPrice();
        }

        foreach ($containerField->GetContainerManager()->GetFields(false, false, false) as $field)
        {
            $this->Quantity += $field->Calculator->GetQuantity();
        }

        if (!ArrayUtils::Some($containerField->GetContainerManager()->GetFields(false, false, false),
            function ($item) {
                return $item->Options->PriceType == "quantity" || $item->Options->PriceType == "quantity_per_day";
            }))
        {
            $this->Quantity = 1;
        }


        $this->OptionsTotal=$this->OptionsUnitPrice*$this->Quantity;
        $this->GrandTotal=($this->OptionsUnitPrice+$this->GetPrice())*$this->Quantity;

        return $this->CreateCalculationObject($this->OptionsUnitPrice,'',$this->Quantity);
    }
}