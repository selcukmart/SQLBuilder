<?php
/**
 * @author selcukmart
 * 15.05.2021
 * 18:52
 */

namespace SelcukMart\Tools;


class Migration
{

    public function __construct()
    {
    }

    public function scan()
    {
        $dir = ADMIN_DIR;
        $lists = scandir($dir);
        foreach ($lists as $index => $list) {
            $list = ADMIN_DIR . '/' . $list;
            if (is_dir($list)) {
                $files = scandir($list);
                foreach ($files as $index => $file) {
                    $sql_file = $list . '/sql.php';
                    if (file_exists($sql_file)) {
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
        // TODO: Implement __destruct() method.
    }
}