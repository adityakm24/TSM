<?php


namespace rednaoeasycalculationforms\core\Managers\EntrySaver;


class AIOEntry
{
    public $UserId;
    public $Sequence;
    public $FormattedSequence;
    public $UnixDate;
    public $Data;
    public $Total;
    public $Status;
    public $EntryId;
    public $FormId;
    public $UserName;
    public $IsVisible;
    public $UserEmail;
    public $ReferenceId;
    /** @var AIOMeta[] */
    public $Meta;
    public $EditNonce;

    public function __construct()
    {
        $this->Meta=[];
    }


}