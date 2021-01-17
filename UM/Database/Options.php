<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Options
{
    public static function insert()
    {

    }


    

    /**
     * select array value from options table
     * 
     * @param string $meta_key
     * 
     * @return array $meta_value
     * 
     * @version 1.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function select( $meta_key )
    {
        $meta_value = DB::select('SELECT meta_value FROM UM_options WHERE `meta_key`=?', [ htmlspecialchars(trim($meta_key)) ])[0]->meta_value;

        $meta_value = json_decode($meta_value, true);
        return $meta_value;
    }
}