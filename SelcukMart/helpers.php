<?php
/**
 * @author selcukmart
 * 31.01.2022
 * 14:51
 */

function _sizeof($data): int
{
    if (PHP_VERSION_ID > 70300) {
        if (is_countable($data)) {
            return count($data);
        }
    }
    return is_array($data) ? count($data) : false;
}

function c($v, $return = false)
{
    if ($return) {
        $cikti = '<pre>';
    } else {
        echo '<pre>';
    }
    if (is_array($v) || is_object($v)) {
        if ($return) {
            $cikti .= print_r($v, true);
        } else {
            print_r($v, false);
        }
    } else {
        if ($return) {
            $cikti .= $v;
        } else {
            if (is_bool($v)) {
                var_dump($v);
            } else {
                echo $v;
            }
        }
    }
    if ($return) {
        $cikti .= '</pre>';
        return $cikti;
    }

    echo '</pre>';
}