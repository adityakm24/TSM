<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class MultipleStepItemDTO extends StoreBase{
	/** @var Numeric */
	public $Id;
	/** @var string */
	public $Title;
	/** @var Numeric[] */
	public $FieldIds;
	/** @var IconOptionsDTO */
	public $Icon;
	/** @var IconOptionsDTO */
	public $Image;


	public function LoadDefaultValues(){
		$this->Id=0;
		$this->Title='';
		$this->FieldIds=[];
		$this->Icon=(new IconOptionsDTO())->Merge();
		$this->Image=(new IconOptionsDTO())->Merge();
		$this->AddType("FieldIds","Numeric");
	}
}

