<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Options
{
    public static function set()
    {

    }


    

    /**
     * get array value from options table
     * 
     * @param string $meta_key
     * @return array $meta_value
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function get( $meta_key )
    {
        $meta_value = DB::select('SELECT meta_value FROM UM_options WHERE `meta_key`=?', [$meta_key])[0]->meta_value;

        $meta_value = json_decode($meta_value, true);
        return $meta_value;
    }
}