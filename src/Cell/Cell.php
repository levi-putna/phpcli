<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 2:24 PM
 */

namespace PHPCli\Cell;


class Cell implements CellFormatInterface{

    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName)
    {
       return $value;
    }
}