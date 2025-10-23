<?php

declare(strict_types=1);

/**
 * Helper functions for SQLBuilder legacy support
 *
 * @author selcukmart
 * @since 1.0
 * @deprecated These functions are maintained for backward compatibility only
 */

/**
 * Count elements safely with proper type safety
 *
 * @param mixed $data
 * @return int
 */
function _sizeof(mixed $data): int
{
    if (is_countable($data)) {
        return count($data);
    }

    if (is_array($data)) {
        return count($data);
    }

    return 0;
}

/**
 * Debug output function
 *
 * @param mixed $v
 * @param bool $return
 * @return string|null
 */
function c(mixed $v, bool $return = false): ?string
{
    $output = '';

    if ($return) {
        $output = '<pre>';
    } else {
        echo '<pre>';
    }

    if (is_array($v) || is_object($v)) {
        if ($return) {
            $output .= print_r($v, true);
        } else {
            print_r($v);
        }
    } elseif ($return) {
        $output .= (string) $v;
    } elseif (is_bool($v)) {
        var_dump($v);
    } else {
        echo $v;
    }

    if ($return) {
        $output .= '</pre>';
        return $output;
    }

    echo '</pre>';
    return null;
}