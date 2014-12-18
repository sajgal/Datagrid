<?php namespace Datagrid\Renderer;


use Datagrid\BasicElements\Row;
use Datagrid\Utils\Parser;
use Nette\Utils\Html;

class DefaultRenderer
{
    private $htmlTableClass = '';
    private $headerLabels = array();
    private $sorting = false;

    public function getTable($data)
    {
        $rows = (new Parser)->parseData($data);
        $table = $this->buildTable($rows);

        return $table;
    }

    private function buildTable(array $rows)
    {
        $table = Html::el('table');

        $this->buildTableClass($table);
        $this->buildHeaderRow($table);
        $this->buildTableRows($table, $rows);

        return $table;
    }

    private function buildTableClass($table)
    {
        if (!empty($this->htmlTableClass)) {
            $table->addAttributes(array('class' => $this->htmlTableClass));
        }
    }

    private function buildHeaderRow($table)
    {
        if (!empty($this->headerLabels)) {
            $headerRow = new Row($this->headerLabels);
            $thead = $headerRow->renderHeaderRow($this->sorting);

            $table->add($thead);
        }
    }

    private function buildTableRows($table, $rows)
    {
        foreach ($rows as $row) {
            $tr = $row->renderRow();
            $table->add($tr);
        }
    }

    public function setHtmlTableClass($htmlTableClass)
    {
        $this->htmlTableClass = $htmlTableClass;
    }

    public function setHeader(array $headerLabels)
    {
        $this->headerLabels = $headerLabels;
    }

    public function enableSorting()
    {
        $this->sorting = true;
    }
}