<?php 

namespace rednaoeasycalculationforms\DTO;

class ChainedSelectFieldOptionsDTO extends FieldWithPriceOptionsDTO{
	/** @var ChainedSelectItemDTO[] */
	public $Items;
	/** @var Boolean */
	public $ReadOnly;
	/** @var String[] */
	public $Columns;
	public $Alignment;
	/** @var string */
	public $EmptyPlaceholder;


	public function LoadDefaultValues(){
		parent::LoadDefaultValues();
		$this->Items=[];
		$this->ReadOnly=false;
		$this->Type='chained';
		$this->Columns=[];
		$this->Alignment='h';
		$this->Label='Chained Select';
		$this->EmptyPlaceholder='Select an item';
		$this->AddType("Items","ChainedSelectItemDTO");
		$this->AddType("Columns","String");
	}
}

