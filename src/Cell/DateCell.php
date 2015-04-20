<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 2:56 PM
 */

namespace PHPCli\Cell;


/**
 * Class DateCell
 *
 * A date format cell.
 *
 * @package PHPCli\Cell
 */
class DateCell implements CellFormatInterface{

    private $date_format;
    private $empty_record;

    function __construct($date_format = 'Y-m-d', $empty_record = '')
    {
        $this->date_format = $date_format;
        $this->empty_record = $empty_record;
    }


    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName)
    {
        if (!$value) {
            return $this->empty_record;
        }
        return date($this->date_format, $value);
    }
}