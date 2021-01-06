<?php

namespace UM\User;


use UM\Database\Usermeta;
use UM\Database\Users;


class Common
{      


    /**
     * find out a user is verified or not by user_id
     * 
     * 
     * @param int $user_id
     * 
     * 
     * @return bool true  - user is     verified
     *              false - user is not verified
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */

    public static function user_is_verified( int $user_id )
    {
        $email_exist = Users::email_id( $user_id );

        if( $email_exist==false || $email_exist==NULL ){
            return false;
        }else{
            $email_is_verified = Usermeta::get( $user_id, 'email_is_verified' );
            if( $email_is_verified=='yes' ){
                $email_is_verified = true;
            }elseif( $email_is_verified=='no' ){
                $email_is_verified = false;
            }
            
            if( $email_is_verified==false ){
                return false;
            }elseif( $email_is_verified==true ){
                return true;
            }
        }

        return false;
    }


    /**
     * find out current logged in user_id
     * 
     * 
     * @return int user_id -  0  means no logged in user
     *                     - >0  logged in user_id
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function current_user_id()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if( empty($_SESSION['UM_login']) && empty($_COOKIE['UM_login']) ){
            return 0;
        }

        if( isset($_SESSION['UM_login']) ){
            $UM_login = $_SESSION['UM_login'];
        }elseif( isset($_COOKIE['UM_login']) ){
            $UM_login = $_COOKIE['UM_login'];
        }
        $UM_login = json_decode($UM_login, true);
        $user_id         = (int)    $UM_login['user_id'];
        $username        = (string) $UM_login['username'];
        $email           = (string) $UM_login['email'];
        $password_hashed = (string) $UM_login['password_hashed'];

        // find out user is verified or not and take action
        $user_is_verified = self::user_is_verified( $user_id );

        if( $user_is_verified==false ){
            return 0;
        }elseif( $user_is_verified==true ){
            $db_username        = Users::username_id( $user_id );
            $db_email           = Users::email_id( $user_id );
            $db_password_hashed = Users::password_hashed_get( $user_id );

            if( $username==$db_username && $email==$db_email && $password_hashed==$db_password_hashed ){
                return $user_id;
            }else{
                return 0;
            }
        }

        
        return 0;
    }


    /**
     * find out user type by user_id
     * 
     * 
     * @param int $user_id
     * 
     * 
     * @return string  'guest | visitor | vendor | admin'
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function user_type( int $user_id )
    {
        if( $user_id==0 ){
            return 'guest';
        }elseif( $user_id>0 ){
            $user_type = Usermeta::get( $user_id, 'user_type' );
            return $user_type;
        }


        return 'guest';
    }



    /**
     * 
     * get the user ip address
     * 
     * @return string $ip
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function user_ip()
    {
        $ip = '';
        if($_SERVER['REMOTE_ADDR']){
            $ip= $_SERVER['REMOTE_ADDR'];
        }else{
            $ip = 'UNKNOWN';
        }
        return (string) $ip;
    }
}