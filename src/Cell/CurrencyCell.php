<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 3:14 PM
 */

namespace PHPCli\Cell;


class CurrencyCell extends NumberCell{

    public static $before = 1;
    public static $after = 2;

    protected $symble;
    protected $position;

    function __construct($symble = '$', $position = null, $decimals = 0, $dec_point = ".", $thousands = ",")
    {
        $this->symble = $symble;
        $this->position = $position;
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
        $number = number_format($value , $this->decimals,$this->dec_point, $this->thousands);

        if($this->position == static::$after){
            return $number . ' ' . $this->symble;
        };

        return $this->symble . ' ' . $number;
    }
}