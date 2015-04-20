<?php
namespace PHPCli\Cell;

interface CellFormatInterface {

    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName);

}