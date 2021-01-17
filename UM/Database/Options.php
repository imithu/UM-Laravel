<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Options
{
    /**
     * insert array value in options table
     * 
     * @param string $meta_key
     * @param array  $meta_value = [ key=>value, key=>value ]
     * 
     * 
     * @version 1.0.0
     * @since 1.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function insert( string $meta_key, array $meta_value )
    {
        $meta_key = htmlspecialchars(trim($meta_key));

        foreach( $meta_value as $key=>$value ){
            $meta_value[$key] = htmlspecialchars($value);       // don't trim side space here.
        }
        $meta_value = json_encode($meta_value);
        
        DB::insert( 'INSERT INTO UM_options (meta_key, meta_value) VALUES( ?, ? ) ', [ $meta_key, $meta_value ] );
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




    /**
     * delete an option from options table
     * 
     * @param string $meta_key
     * 
     * 
     * @version 1.0.0
     * @since 1.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function delete( $meta_key )
    {
        DB::delete( 'DELETE FROM UM_options WHERE meta_key=?', [ htmlspecialchars(trim($meta_key)) ] );
    }
}