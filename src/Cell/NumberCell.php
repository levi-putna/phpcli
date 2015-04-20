<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 3:08 PM
 */

namespace PHPCli\Cell;


class NumberCell implements CellFormatInterface
{

    protected $decimals;
    protected $dec_point;
    protected $thousands;

    function __construct($decimals = 0, $dec_point = ".", $thousands = ",")
    {
        $this->decimals = $decimals;
        $this->dec_point = $dec_point;
        $this->thousands = $thousands;
    }

    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName)
    {
        return number_format ($value , $this->decimals,$this->dec_point, $this->thousands);
    }
}