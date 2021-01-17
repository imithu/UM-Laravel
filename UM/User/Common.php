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