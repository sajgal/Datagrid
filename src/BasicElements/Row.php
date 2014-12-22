<?php namespace Datagrid\BasicElements;


use Datagrid\Utils\Parser;
use Nette\Utils\Html;

class Row implements \Countable, IBasicElement
{
    public $cells = array();
    public $rowActions = array();
    /**
     * @var array
     */
    private $hidedColumns;

    public function __construct(array $rowData, array $hidedColumns)
    {
        $parser = new Parser();
        $this->cells = $parser->rowDataToCells($rowData);
        $this->hidedColumns = $hidedColumns;
    }

    public function count()
    {
        return count($this->cells);
    }

    public function render()
    {
        $tr = Html::el('tr');

        /** @var Cell $cell */
        foreach ($this->cells as $cell) {
            if($this->cellShouldBeHided($cell)) {
                continue;
            };

            $td = $cell->render();
            $tr->add($td);
        }

        if(!empty($this->rowActions)) {
            $actions = new ActionCell($this->rowActions, $this);
            $actionTd = $actions->render();

            $tr->add($actionTd);
        }

        return $tr;
    }

    public function renderHeaderRow($sortingEnabled)
    {
        $thead = Html::el('thead');
        $tr = Html::el('tr');

        $thead->add($tr);

        /** @var Cell $cell */
        foreach ($this->cells as $cell) {
            if($this->cellShouldBeHided($cell)) {
                continue;
            };

            $td = $cell->renderHeaderCell($sortingEnabled);
            $tr->add($td);
        }

        return $thead;
    }

    public function getCellByCellName($cellName)
    {
        $cell = array_filter($this->cells, function (Cell $cell) use ($cellName) {
            return $cell->getColumnName() == $cellName;
        });

        return reset($cell);
    }

    public function addActions(array $rowActions)
    {
        $this->rowActions = $rowActions;
    }

    private function cellShouldBeHided(Cell $cell)
    {
        if (in_array($cell->getColumnName(), $this->hidedColumns)) {
            return true;
        }

        return false;
    }
}