<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Users
{
    /**
     * 
     * get user id by username
     * 
     * @param string $username
     * 
     * @return int $id
     *             0 if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function id_username( string $username )
    {
        $id = DB::select('SELECT id FROM UM_users WHERE    `username`=? ORDER BY id DESC', [$username])[0]->id ?? 0;
        return $id;
    }


    /**
     * 
     * get user id by email
     * 
     * @param string $email
     * 
     * @return int $id
     *             0 if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function id_email( string $email )
    {
        $id = DB::select('SELECT id FROM UM_users WHERE    `email`=? ORDER BY id DESC', [$email])[0]->id ?? 0;
        return $id;
    }


    /**
     * 
     * get user id by username or email
     * 
     * @param string $username_or_email
     * 
     * @return int $id
     *             0 if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function id_username_or_email( string $username_or_email )
    {
        $user_id = self::id_username( $username_or_email );
        
        if( $user_id==0 ){
            $user_id = self::id_email( $username_or_email );
        }

        if( $user_id==0 ){
            return 0;
        }elseif( $user_id>0 ){
            return $user_id;
        }

        return $user_id;
    }

}