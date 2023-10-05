<?php 

namespace rednaoeasycalculationforms\DTO;

class ButtonSelectionFieldOptionsDTO extends MultipleOptionsBaseOptionsDTO{
	public $ImagePosition;
	/** @var Boolean */
	public $AllowMultipleSelection;
	/** @var Numeric */
	public $NumberOfColumns;
	/** @var Boolean */
	public $ShowPriceInEachButton;
	/** @var ButtonItemOptionsDTO[] */
	public $Options;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Label='Button Selection';
		$this->Type=FieldTypeEnumDTO::$ButtonSelection;
		$this->ImagePosition='left';
		$this->AllowMultipleSelection=false;
		$this->NumberOfColumns=3;
		$this->ShowPriceInEachButton=true;
		$this->Options=[];
		$this->AddType("Options","ButtonItemOptionsDTO");
	}
}

