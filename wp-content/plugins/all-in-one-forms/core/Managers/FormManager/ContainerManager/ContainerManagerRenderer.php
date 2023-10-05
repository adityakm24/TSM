<?php

namespace rednaoeasycalculationforms\core\Managers\FormManager\ContainerManager;

use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Context\HTMLContextBase;
use rednaoeasycalculationforms\core\Managers\HTMLGenerator\Renderers\Core\RendererBase;

class ContainerManagerRenderer extends RendererBase
{
    /** @var HTMLContextBase */
    public $Context;
    /** @var ContainerManager */
    public $ContainerManager;
    /** @var RowRenderer[] */
    public $Rows=[];

    /**
     * @param $containerManager ContainerManager
     */
    public function __construct($containerManager,$context)
    {
        parent::__construct($containerManager->Container->GetLoader());
        $this->ContainerManager=$containerManager;
        $this->Context=$context;
    }


    protected function GetTemplateName()
    {
        return 'core/Managers/FormManager/ContainerManager/ContainerManager.twig';
    }


    protected function PrepareContent()
    {
        $this->Rows=[];
        $this->PrepareRows($this->ContainerManager);
    }

    public function GetMaximumColumnsPerRow(){
        $cols=0;
        foreach($this->Rows as $currentRow)
            $cols=max(count($currentRow->Columns),$cols);

        return $cols;
    }

    private function PrepareRows(ContainerManager $ContainerManager)
    {
        foreach($ContainerManager->Container->GetRows() as $currentRow)
        {
            $newRow=null;
            foreach($currentRow->Columns as $currentColumn)
            {
                if(!$currentColumn->Field->IsUsed())
                    continue;
                if(isset($currentColumn->Field->ContainerManager)) {
                    /** @var TemplateColumnRenderer[] $headers */
                    $headers=$currentColumn->Field->ContainerManager->GetHeaderColumns();
                    foreach($headers as $currentHeader)
                    {
                        $newRow=new RowRenderer($this->loader,$this);
                        $this->Rows[]=$newRow;
                        $newRow->Columns[]=$currentHeader;

                        $currentHeader->Row=$newRow;
                    }
                    $this->PrepareRows($currentColumn->Field->ContainerManager);
                }
                else
                {
                    if($newRow==null) {
                        $newRow = new RowRenderer($this->loader,$this);
                        $this->Rows[]=$newRow;
                    }
                    $newRow->Columns[]=new ColumnRenderer($this->loader,$newRow,$currentColumn);
                }
            }
        }
    }
}