<?php

namespace Nv2\Model\Entity\Schedule\RouteSchedules;

use Nv2\Model\Entity\Base\Entity;
use Nv2\Model\Entity\Transport\Route;

class Result extends Entity
{
    public $TableRowList;
    public $Route;

    private function __construct()
    {
        $this->TableRowList = null;
        $this->Route = null;
    }

    public static function create()
    {
        return new self();
    }

    public function fill($feed)
    {
        foreach ($feed->table->rows as $row) {
            $rowObject = TableRow::create()
                ->fill($row);
            $this->addTableRow($rowObject);
        }

        $this->Route = Route::create();
        if (isset($feed->route)) {
            $this->Route->fill($feed->route);
        }

        return $this;
    }

    public function addTableRow(TableRow $row)
    {
      $this->TableRowList[] = $row;
    }
}