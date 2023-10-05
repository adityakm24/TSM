<?php

namespace rednaoeasycalculationforms\Parser\Core;

use rednaoeasycalculationforms\core\Managers\FormManager\FormBuilder;

class DataRetriever
{
    static $GlobalFunctions=[];
    public $VarDictionary=[];
    public $FunctionDictionary=[];
    /** @var FormBuilder */
    public $FormBuilder;
    public $Attributes=[];

    public function __construct($formBuilder)
    {
        $this->FunctionDictionary=self::$GlobalFunctions;
        $this->FormBuilder=$formBuilder;
    }

    public function GetFieldById($fieldId)
    {
        return $this->FormBuilder->GetFieldById($fieldId);
    }

    public function SetVar($varName,$value)
    {
        $this->VarDictionary[$varName]=$value;
    }

    public function &GetVar($varName)
    {
        if(isset($this->VarDictionary[$varName]))
            return $this->VarDictionary[$varName];
        $null=null;
        return $null;
    }

    public function AddFunction($name,$callBack)
    {
        $this->FunctionDictionary[$name]=$callBack;
    }

    public function CallFunction($name,$args)
    {
        if(isset($this->FunctionDictionary[$name]))
        {
            $callBack=$this->FunctionDictionary[$name];
            return $callBack($this,...$args);
        }
        return null;
    }

    public function GetAttribute($name)
    {
        if(isset($this->Attributes[$name]))
            return $this->Attributes[$name];
        return '';
    }

    public function AddAttribute($name,$value)
    {
        $this->Attributes[$name]=$value;
    }


}