<?php


namespace rednaoeasycalculationforms\core\Managers\FormManager\Fields;


use Exception;
use rednaoeasycalculationforms\core\db\core\DBManager;
use rednaoeasycalculationforms\core\db\core\FBFieldWithFiles;
use rednaoeasycalculationforms\core\db\core\FileItem;
use rednaoeasycalculationforms\core\db\FormRepository;
use rednaoeasycalculationforms\core\db\SettingsRepository;
use rednaoeasycalculationforms\core\Managers\FileManager\FileManager;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLEmailContext;
use rednaoeasycalculationforms\core\Managers\SlateGenerator\Core\HtmlTagWrapper;
use rednaoeasycalculationforms\core\Utils\IdUtils;
use rednaoeasycalculationforms\DTO\GoogleMapsFieldOptionsDTO;

class FBGoogleMaps extends FBFieldWithFiles
{
    /** @var GoogleMapsFieldOptionsDTO */
    public $Options;
    public function GetValue()
    {
        return $this->GetEntryValue('Value','');
    }



    public function PrepareForSerialization()
    {
        parent::PrepareForSerialization();
        if($this->Options->ShowMap &&isset($this->Entry->Value->Latitude)&&$this->Entry->Value->Latitude!=0)
        {
            $value=$this->Entry->Value;
            $form=$form=$this->GetRootForm();
            $url='';
            $settingsRepository=new SettingsRepository($this->Loader);
            $apiKey=$settingsRepository->GetGoogleMapsApiKey();
            if($apiKey!='')
            {

                $url="https://maps.googleapis.com/maps/api/staticmap?center=";
                if($value->MarkerLongitude!=0||$value->MarkerLatitude!=0)
                {
                    $url .= $value->MarkerLatitude . ',' . $value->MarkerLongitude;
                }
                else
                    $url.=$value->Latitude.','.$value->Longitude;

                foreach($this->Entry->Value->Markers as $currentMarker)
                {
                    $url.='&markers='.$currentMarker->MarkerLatitude.','.$currentMarker->MarkerLongitude;
                }

                $url.='&size=600x300';
                $url.='&zoom='.$value->Zoom;
                $url.='&maptype=roadmap';
                $url.='&key='.$apiKey;

                $formRepository=new FormRepository($this->Loader);

                $data=\file_get_contents($url);
                $fileManager=new FileManager($this->Loader);
                $path=$fileManager->GetMapsFolderRootPath();
                $fileName=$fileManager->GetSafeFileName($path,uniqid("",true).'.png');
                \file_put_contents($path.$fileName,$data);
                $this->Entry->Value->FileName=$fileName;
                $this->Entry->Value->EntryReference=$this->GetRootForm()->GetReference();
                $this->Entry->Value->FileReference=IdUtils::GetUniqueId();
                $this->Entry->Value->UploadDate=date('c');
                $this->Entry->Value->FileId=$formRepository->GetNextFileId();
                $dbManager=new DBManager();

                $fileItem=new FileItem();
                $fileItem->MimeType='image/png';
                $fileItem->EntryReference=$this->Entry->Value->EntryReference;
                $fileItem->UploadDate=$this->Entry->Value->UploadDate;
                $fileItem->FileReference=$this->Entry->Value->FileReference;
                $fileItem->FileSequenceId=$this->Entry->Value->FileId;
                $fileItem->Name='';
                $fileItem->PhysicalName=$this->Entry->Value->FileName;
                $fileItem->FileType='map';
                $fileItem->FieldId=$this->Options->Id;

                $this->AddFile($fileItem);

            }

        }


    }

    public function InternalToText()
    {
        $address=[];
        if(trim($this->Entry->Value->Address1)!='')
            $address[]=trim($this->Entry->Value->Address1);
        if(trim($this->Entry->Value->Address2)!='')
            $address[]=trim($this->Entry->Value->Address2);
        if(trim($this->Entry->Value->City)!='')
            $address[]=trim($this->Entry->Value->City);
        if(trim($this->Entry->Value->State)!='')
            $address[]=trim($this->Entry->Value->State);
        if(trim($this->Entry->Value->Zip)!='')
            $address[]=trim($this->Entry->Value->Zip);
        if(trim($this->Entry->Value->CountryLong)!='')
            $address[]=trim($this->Entry->Value->CountryLong);


        return \implode(', ',$address);
    }

    public function GetAddress1(){
        return $this->Entry->Value->Address1;
    }

    public function GetAddress2(){
        return $this->Entry->Value->Address2;
    }

    public function GetCity(){
        return $this->Entry->Value->City;
    }

    public function GetState(){
        return $this->Entry->Value->State;
    }

    public function GetZip(){
        return $this->Entry->Value->Zip;
    }

    public function GetCountry(){
        return $this->Entry->Value->CountryLong;
    }

    public function GetMapURL($context=null){
        if($this->Options->ShowMap==false&&isset($this->Entry->Value->FileId))
            return '';


        $filemanager=new FileManager($this->Loader);



        if($context instanceof HTMLEmailContext) {
            $fileData=$filemanager->GetFileData($this->Entry->Value->FileId,$this->Entry->Value->FileReference);
            $id=$context->AddInlineImage($fileData->Path);
            return 'cid:'.$id;
        }

        return $filemanager->GetDownloadLink($this->Entry->Value->FileId,$this->Entry->Value->FileReference);
    }


    public function GetAddresses(){
        $addressList=[];
        $entry=$this->Entry->Value;

        foreach($entry->Markers as $currentMarker) {
            $sections=[];
            if ($currentMarker->Address->StreetAddress1 != '') {
                $sections[] = [$currentMarker->Address->StreetAddress1];
            }

            if ($currentMarker->Address->StreetAddress2 != ''&&$this->Options->ShowAddress2) {
                $sections[] = [$currentMarker->Address->StreetAddress2];
            }


            $cityAndState = [];
            if ($currentMarker->Address->City != ''&&$this->Options->ShowCity) {
                $cityAndState[] = $currentMarker->Address->City;
            }

            if ($currentMarker->Address->State != ''&&$this->Options->ShowState) {
                $cityAndState[] = $currentMarker->Address->State;
            }

            if (count($cityAndState) > 0)
                $sections[] = $cityAndState;


            if ($currentMarker->Address->Zip != ''&&$this->Options->ShowZip) {
                $sections[] = [$currentMarker->Address->Zip];
            }

            if ($currentMarker->Address->CountryLong != ''&&$this->Options->ShowCountry) {
                $sections[] = [$currentMarker->Address->CountryLong];
            }

            $addressList[]=$sections;
        }

        return $addressList;
    }


    public function GetHTMLTemplate($context=null)
    {
        return "core/Managers/FormManager/Fields/FBGoogleMaps.twig";
    }


    public function GetLineItems(){
        if($this->Entry==null||$this->GetEntryValue('Value','')==='')
            return array();

        $baseItem=parent::GetLineItems()[0];
        if($baseItem==null)
            return null;

        $path='';
        $fileName='';
        $itemList=[];

        $settingsRepository=new SettingsRepository($this->Loader);
        $apiKey=$settingsRepository->GetGoogleMapsApiKey();

        if(count($this->Entry->Value->Markers)>=0)
        {
            foreach($this->Entry->Value->Markers as $currentMarket)
            {
                $item=$baseItem->CloneItem();

                $item->Value=$currentMarket->MarkerLatitude.','.$currentMarket->MarkerLongitude;
                $item->NumericValue=$currentMarket->MarkerLatitude;
                $item->NumericValue2=$currentMarket->MarkerLongitude;

                $item->ExValue1=$currentMarket->Address->StreetAddress1;
                $item->ExValue2=$currentMarket->Address->StreetAddress2;
                $item->ExValue3=$currentMarket->Address->City;
                $item->ExValue4=$currentMarket->Address->State;
                $item->ExValue5=$currentMarket->Address->Zip;
                $item->ExValue6=$currentMarket->Address->CountryLong;
                $itemList[]=$item;
            }


        }

        return $itemList;
    }

}