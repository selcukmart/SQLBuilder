<?php
/**
 * @author selcukmart
 * 14.05.2021
 * 19:20
 */

namespace SelcukMart\SQLOperations;

class SQLBuilderHook
{
    private
        $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * $sql_hook = [
     *  key => join,
     *  order=>20,
     *  position=>append/prepend
     * ]
     * @param array $sql_hooks
     * @author selcukmart
     * 14.05.2021
     * 19:23
     */
    public function add(array $sql_hooks)
    {
        if (!isset($sql_hooks[0])) {
            $sql_hooks = [$sql_hooks];
        }
        foreach ($sql_hooks as $sql_hook) {
            $this->addCore($sql_hook);
        }
    }

    private function addCore($sql_hook)
    {
        global $sql_builder_hook_values_array;
        if (is_null($sql_builder_hook_values_array)) {
            $sql_builder_hook_values_array = [];
        }

        $key = self::key($sql_hook['key'], $sql_hook['position'], $this->id);

        if (isset($sql_hook['order']) && is_numeric($sql_hook['order'])) {
            $sql_builder_hook_values_array[$key][$sql_hook['order']] = $sql_hook['str'];
        } else {

            $sql_builder_hook_values_array[$key][] = $sql_hook['str'];

        }
    }

    public static function key($key, $position, $id)
    {
        return mb_strtoupper($key) . '_' . mb_strtoupper($position) . '_' . $id;
    }

    /**
     * @return string
     */
    public function get($key): string
    {
        global $sql_builder_hook_values_array;
        $str = '';
        if (isset($sql_builder_hook_values_array[$key])) {
            $hooks = $sql_builder_hook_values_array[$key];
            ksort($hooks);
            foreach ($hooks as $index => $hook) {
                $str .= ' ' . $hook;
                unset($sql_builder_hook_values_array[$key][$index]);
            }
        }

        return $str;
    }

    public static function lists()
    {
        global $sql_builder_hook_values_array;
        return $sql_builder_hook_values_array;
    }

    public function __destruct()
    {
//        global $sql_builder_hook_values_array;
//        c($sql_builder_hook_values_array);
    }
}