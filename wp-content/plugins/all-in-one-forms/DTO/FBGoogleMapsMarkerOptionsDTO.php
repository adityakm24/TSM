<?php 

namespace rednaoeasycalculationforms\DTO;

use rednaoeasycalculationforms\DTO\core\StoreBase;

class FBGoogleMapsMarkerOptionsDTO extends StoreBase{
	/** @var Numeric */
	public $MarkerLatitude;
	/** @var Numeric */
	public $MarkerLongitude;
	/** @var GoogleMapsAddressDTO */
	public $Address;


	public function LoadDefaultValues(){
		$this->MarkerLatitude=0;
		$this->MarkerLongitude=0;
		$this->Address=(new GoogleMapsAddressDTO())->Merge();
	}
}

