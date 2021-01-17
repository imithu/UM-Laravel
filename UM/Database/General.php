<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class General
{
    /**
     * select all the data based on meta_key from UM_general table
     * 
     * @param string $meta_key
     * 
     * @return array all data of the meta_key
     * 
     * @version 1.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function select_all( string $meta_key )
    {
        $get_all = DB::select('SELECT meta_value FROM UM_general WHERE `meta_key`=? ;', [$meta_key])[0]->meta_value;
        return json_decode($get_all, true);
    }



    /**
     * get the id of json meta_value by value
     * 
     * @param string $meta_key
     * @param string $value
     * 
     * @return int id of the value of a json meta_value
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function get_id( string $meta_key, string $value ): int
    {
        $pair_all = self::get_all( $meta_key );

        foreach( $pair_all as $pair_id=>$pair_value ){
            if( $pair_value==$value ){
                $id = $pair_id;
                break;
            }
        }

        return $id;
    }



    /**
     * get the name of json meta_value by id
     * 
     * @param string $meta_key
     * @param int    $id
     * 
     * @return string name of the value of a json meta_value
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function get_value( string $meta_key, int $id ): string
    {
        $pair_all = self::get_all( $meta_key );

        foreach( $pair_all as $pair_id=>$pair_value ){
            if( $pair_id==$id ){
                $value = $pair_value;
                break;
            }
        }

        return $value;
    }


}