<?php
/**
 * @author selcukmart
 * 14.05.2021
 * 18:53
 */

namespace SelcukMart\SQLOperations;


class SELECTInjection
{
    private
        $sql;

    public function __construct(array $sql)
    {
        $this->sql=$sql;
    }

    public function inject($sql_part)
    {
        if (isset($this->sql[0]['type']) && $this->sql[0]['type'] === 'SELECT') {
            $this->sql[0][] = $sql_part;
        } elseif (isset($this->sql['SELECT'])) {
            $this->sql['SELECT'][] = $sql_part;

        }
        return $this->sql;
    }

    public function __destruct()
    {
        
    }
}