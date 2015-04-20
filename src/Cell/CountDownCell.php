<?php
/**
 * Created by PhpStorm.
 * User: leviputna
 * Date: 20/04/15
 * Time: 3:25 PM
 */

namespace PHPCli\Cell;


class CountDownCell implements CellFormatInterface{

    /**
     * @param $value The value to be formatted
     * @return string The formatted string to render in a cell
     */
    public function format($value, $row, $fieldName)
    {
        if (!$value) {
            return '';
        } else {
            $isPast = false;
            if ($value > time()) {
                $seconds = $value - time();
            } else {
                $isPast = true;
                $seconds = time() - $value;
            }
            $text = $seconds . ' second' . ($seconds == 1 ? '' : 's');
            if ($seconds >= 60) {
                $minutes = floor($seconds / 60);
                $seconds -= ($minutes * 60);
                $text = $minutes . ' minute' . ($minutes == 1 ? '' : 's');
                if ($minutes >= 60) {
                    $hours = floor($minutes / 60);
                    $minutes -= ($hours * 60);
                    $text = $hours . ' hours, ' . $minutes . ' minute' . ($hours == 1 ? '' : 's');
                    if ($hours >= 24) {
                        $days = floor($hours / 24);
                        $hours -= ($days * 24);
                        $text = $days . ' day' . ($days == 1 ? '' : 's');
                        if ($days >= 365) {
                            $years = floor($days / 365);
                            $days -= ($years * 365);
                            $text = $years . ' year' . ($years == 1 ? '' : 's');
                        }
                    }
                }
            }
            return $text . ($isPast ? ' ago' : '');
        }
    }
}