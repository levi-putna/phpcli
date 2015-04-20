<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 3:23 PM
 */

namespace PHPCli\Cell;


class BoolCell implements CellFormatInterface{

    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName)
    {
        return ($value)? 'True' : 'False';
    }
}