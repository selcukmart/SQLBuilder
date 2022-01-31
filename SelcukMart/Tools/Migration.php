<?php
/**
 * @author selcukmart
 * 15.05.2021
 * 18:52
 */

namespace SelcukMart\Tools;


class Migration
{

    const MIGRATION_DIR = '';

    public function __construct()
    {
    }

    public function scan()
    {
        $dir = self::MIGRATION_DIR;
        $lists = scandir($dir);
        foreach ($lists as $index => $list) {
            $list = self::MIGRATION_DIR . '/' . $list;
            if (is_dir($list)) {
                $files = scandir($list);
                foreach ($files as $file) {

                    $sql_file = $list . '/' . $file;
                    if (is_file($sql_file)) {
                        c($sql_file);
                    }
                }
            }
        }
    }

    private function sqlParser()
    {

    }

    private function sqlBuilder()
    {

    }

    public function __destruct()
    {

    }
}