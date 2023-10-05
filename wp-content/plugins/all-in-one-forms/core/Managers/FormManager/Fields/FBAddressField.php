<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;

class FBAddressField extends FBFieldWithPrice
{
    public function GetLineItems()
    {
        $item= parent::GetLineItems()[0];

        $addressParts=array();
        $entry=$this->Entry->Value;

        if($entry->Address1!='')
        {
            $addressParts[] = $entry->Address1;
            $item->ExValue1=$entry->Address1;
        }

        if($entry->Address2!='')
        {
            $addressParts[] = $entry->Address2;
            $item->ExValue2=$entry->Address2;
        }

        if($entry->City!='')
        {
            $addressParts[] = $entry->City;
            $item->ExValue3=$entry->City;
        }

        if($entry->State!='')
        {
            $addressParts[] = $entry->State;
            $item->ExValue4=$entry->State;;
        }

        if($entry->Zip!='')
        {
            $addressParts[] = $entry->Zip;
            $item->ExValue5= $entry->Zip;
        }

        if($entry->Country!='')
        {
            $addressParts[] = $entry->Country;
            $item->ExValue6=$entry->Country;
        }

        $value=\implode(', ',$addressParts);
        $item->Value=$value;


        return array($item);
    }



    public function GetSections(){
        $sections=[];
        $entry=$this->Entry->Value;
        if($entry->Address1!='')
        {
            $sections[] = [$entry->Address1];
        }

        if($entry->Address2!='')
        {
            $sections[] = [$entry->Address2];
        }


        $cityAndState=[];
        if($entry->City!='')
        {
            $cityAndState[] = $entry->City;
        }

        if($entry->State!='')
        {
            $cityAndState[] = $entry->State;
        }

        if(count($cityAndState)>0)
            $sections[]=$cityAndState;


        if($entry->Zip!='')
        {
            $sections[]= [$entry->Zip];
        }

        if($entry->Country!='')
        {
            $sections[]= [$entry->Country];
        }

        return $sections;
    }

    public function InternalToText()
    {
        $value='';
        $addressParts=array();
        $entry=$this->Entry->Value;

        if($entry->Address1!='')
        {
            $addressParts[] = $entry->Address1;
        }

        if($entry->Address2!='')
        {
            $addressParts[] = $entry->Address2;
        }

        if($entry->City!='')
        {
            $addressParts[] = $entry->City;
        }

        if($entry->State!='')
        {
            $addressParts[] = $entry->State;
        }

        if($entry->Zip!='')
        {
            $addressParts[] = $entry->Zip;
        }

        if($entry->Country!='')
        {
            $addressParts[] = $entry->Country;
        }

        return \implode(', ',$addressParts);
    }

    public function GetHTMLTemplate($context=null)
    {
        return "core/Managers/FormManager/Fields/FBAddressField.twig";
    }

    public function GetSubSections()
    {
        return [
            (object)["PathId"=>"Address1","Column"=>"exvalue1"],
            (object)["PathId"=>"Address2","Column"=>"exvalue2"],
            (object)["PathId"=>"City","Column"=>"exvalue3"],
            (object)["PathId"=>"State","Column"=>"exvalue4"],
            (object)["PathId"=>"Zip","Column"=>"exvalue5"],
            (object)["PathId"=>"Country","Column"=>"exvalue6"],
        ];
    }

}

