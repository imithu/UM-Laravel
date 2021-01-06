<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Usermeta
{

    /**
     * Add meta data of a user
     * 
     * @param int $user_id
     * @param string $meta_key
     * @param string $meta_value
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function set( int $user_id, string $meta_key, string $meta_value )
    {
        date_default_timezone_set('UTC');
        $datetime = date('Y-m-d H:i:s');
        DB::insert('INSERT INTO UM_usermeta  VALUES( NULL, ?, ?, ?, ?)', [$user_id, $meta_key, $meta_value, $datetime]);
    }


    /**
     * get meta_value by user_id and meta_key
     * 
     * @param int $user_id
     * @param string $meta_key
     * 
     * @return string $meta_value
     * @return bool false if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function get( int $user_id, string $meta_key )
    {
        return DB::select('SELECT meta_value FROM UM_usermeta WHERE   (`user_id`=? AND `meta_key`=?) ORDER BY id DESC', [$user_id, $meta_key])[0]->meta_value ?? false;
    }
}