<?php

namespace SelcukMart\Tools;

use PhpMyAdmin\SqlParser\Parser;
use SelcukMart\SQLBuilder;

/**
 * @author selcukmart
 * 14.05.2021
 * 14:45
 */
trait SQLToolsTrait
{

    private function table($option)
    {

        $a = 0;
        foreach ($option as $index => $item) {
            if (is_array($item)) {
                $this->SQLBuilder->build($item);
            } else {
                if ($a == 0) {
                    $this->table = $item;
                }
                if ($a == 1) {
                    $this->table_as = $item;
                }
                $item = $item . ' ';
                $this->setOutput($item);
            }
            $a++;
        }

    }

    private function where($option)
    {

        $arr = [];
        if (is_string($option)) {
            $option = trim($option);
            $option = preg_replace('/^AND/', '', $option);
            $this->setOutput($option);
            return $option;
        }
        foreach ($option as $index => $item) {
            if (is_array($item)) {

                $this->SQLBuilder->build($item);
            } else {
                $item = preg_replace('/\.\s+/', '.', $item);
                $parser = new Parser($item);
                $previous_token = '';
                $index = 0;
                foreach ($parser->list->tokens as $token) {
                    if ($token->type == 0) {
                        if (!empty($this->table_as) && !preg_match('/\./', $previous_token)) {
                            $statement = $this->table_as . '.' . $token->token;
                            //c($statement);
                            $arr[] = $statement;
                        } else {
                            $previous_index = $index - 2;
                            if (isset($arr[$previous_index])) {
                                $arr[$previous_index] = str_replace($this->table_as . '.', '', $arr[$previous_index]);
                            }
                            $arr[] = $token->token;
                        }
                    } else {
                        $arr[] = $token->token;
                    }
                    $previous_token = $token->token;
                    $index++;
                }
            }
        }

        $items = '';
        foreach ($arr as $item) {
            $items .= $item;
        }

        $this->setOutput($items);

        return $items;
    }
}