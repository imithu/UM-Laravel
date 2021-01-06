<?php

namespace UM\Database;


use Illuminate\Support\Facades\DB;


class Users
{

    /**
     * 
     * Add a user in database users table
     * 
     * @param string $username
     * @param string $email
     * @param string $password
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function user_add( string $username, string $email, string $password )
    {
        $password = password_hash( $password, PASSWORD_DEFAULT );

        date_default_timezone_set('UTC');
        $datetime = date('Y-m-d H:i:s');
        DB::insert('INSERT INTO UM_users  VALUES( NULL, ?, ?, ?, ?)', [$username, $email, $password, $datetime]);
    }



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


    /**
     * 
     * get username by id
     * 
     * @param string $id
     * 
     * @return int $username
     * @return bool false if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function username_id( int $id )
    {
        $username = DB::select('SELECT username FROM UM_users WHERE    `id`=? ORDER BY id DESC', [$id])[0]->username ?? false;
        return $username;
    }


    /**
     * 
     * get username by email
     * 
     * @param string $email
     * 
     * @return int $username
     * @return bool false if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function username_email( string $email )
    {
        $username = DB::select('SELECT username FROM UM_users WHERE    `email`=? ORDER BY id DESC', [$email])[0]->username ?? false;
        return $username;
    }


    /**
     * 
     * get email by id
     * 
     * @param string $id
     * 
     * @return int $email
     * @return bool false if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function email_id( int $id )
    {
        $email = DB::select('SELECT email FROM UM_users WHERE    `id`=? ORDER BY id DESC', [$id])[0]->email ?? false;
        return $email;
    }


    /**
     * 
     * get email by username
     * 
     * @param string $username
     * 
     * @return int $email
     * @return bool false if not found
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function email_username( string $username )
    {
        return DB::select('SELECT email FROM UM_users WHERE    `username`=? ORDER BY id DESC', [$username])[0]->email ?? false;
    }



    /**
     * set new email by user_id
     * 
     * @param int    $user_id
     * @param string $email
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */

    public static function email_set( int $user_id, string $email )
    {
        DB::update( 'UPDATE UM_users SET `email`=? WHERE `id`=?', [$email, $user_id] );
    }




    /**
     * 
     * set hashed password
     * 
     * @param int    $user_id
     * @param string $password
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function password_hashed_set( int $user_id, string $password )
    {
        $password = password_hash( $password, PASSWORD_DEFAULT );
        DB::update( 'UPDATE UM_users SET `password`=? WHERE `id`=?', [$password, $user_id] );
    }


    /**
     * 
     * get hashed password
     * 
     * @param int $user_id
     * 
     * @return string $password_hashed
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function password_hashed_get( int $user_id )
    {
        return DB::select('SELECT  `password` FROM UM_users WHERE    `id`=? ORDER BY id DESC', [$user_id])[0]->password;
    }

}