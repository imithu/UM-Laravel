<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Usermeta
{

    /**
     * Insert meta data of an user
     * 
     * @param int    $user_id
     * @param string $meta_key
     * @param string $meta_value
     * @param bool   $unique (optional)
     * 
     * @since   0.0.0
     * @version 1.6.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function insert( int $user_id, string $meta_key, string $meta_value, bool $unique=true )
    {
        date_default_timezone_set('UTC');
        $datetime = date('Y-m-d H:i:s');

        if( $unique ){
            if(count(DB::select('SELECT * FROM UM_usermeta WHERE (user_id=? AND meta_key=?) ORDER BY id DESC', [$user_id, htmlspecialchars(trim($meta_key))]))==0){
                DB::insert('INSERT INTO UM_usermeta  VALUES( NULL, ?, ?, ?, ?)', [$user_id, htmlspecialchars(trim($meta_key)), htmlspecialchars(trim($meta_value)), $datetime]);
            }else{
                if(self::select($user_id, $meta_key)!=htmlspecialchars(trim($meta_value))){
                    DB::update('UPDATE UM_usermeta SET meta_value=?, `datetime`=? WHERE (user_id=? AND meta_key=?)', [ htmlspecialchars(trim($meta_value)), $datetime, $user_id, htmlspecialchars(trim($meta_key)) ]);
                }
            }
        }elseif( $unique==false ){
            DB::insert('INSERT INTO UM_usermeta  VALUES( NULL, ?, ?, ?, ?)', [$user_id, htmlspecialchars(trim($meta_key)), htmlspecialchars(trim($meta_value)), $datetime]);
        }
    }


    /**
     * select meta_value by user_id and meta_key
     * 
     * @param int $user_id
     * @param string $meta_key
     * 
     * @return string meta_value
     *                if not found then return ''
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function select( int $user_id, string $meta_key )
    {
        return DB::select('SELECT meta_value FROM UM_usermeta WHERE   (`user_id`=? AND `meta_key`=?) ORDER BY id DESC', [$user_id, htmlspecialchars(trim($meta_key))])[0]->meta_value ?? '';
    }




    /**
     * delete all meta data by user_id and meta_key
     * 
     * @param int    $user_id
     * @param string $meta_key
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function delete( int $user_id, string $meta_key )
    {
        DB::delete( 'DELETE FROM UM_usermeta WHERE   (`user_id`=? AND `meta_key`=?)', [$user_id, htmlspecialchars(trim($meta_key))] );
    }
}