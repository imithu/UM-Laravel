<?php

namespace UM\User;


use UM\Verify\User;
use UM\Verify\Syntax;
use UM\Generate\Unknown_Data;
use UM\Database\Users;


class Password
{

    /**
     * 
     * password reset request
     * 
     * 
     * @param string username_or_email
     * 
     * @return array   [ user_id, email ,temp_otp ]
     *                 else []
     * 
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function password_reset_request( string $username_or_email )
    {
        $SR = [];
        $user_id = Users::id_username_or_email( $username_or_email );

        if( $user_id>0 && User::user_is_verified( $user_id ) ){
            $temp_otp = Unknown_Data::temp_otp(6);
            $email = Users::select( $user_id, 'email' );
            Users::update( $user_id, 'temp_otp', $temp_otp );

            $SR = 
            [
                'user_id'=>$user_id,
                'email'=>$email,
                'temp_otp'=>$temp_otp
            ];
        }

        return $SR;
    }




    /**
     * update password of an user
     * 
     * @param int    $user_id
     * @param string $temp_otp
     * @param string $password
     * 
     * @return bool - true  - password update successful
     *                false - password update failed
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function password_update( int $user_id, string $temp_otp, string $password )
    {
        $SR = false;

        if( 
               Syntax::password($password) 
            && $user_id>0 
            && User::user_is_verified( $user_id ) 
            && User::temp_otp($user_id, $temp_otp) 
        )
        {
            Users::update( $user_id, 'password', password_hash($password, PASSWORD_DEFAULT) );
            $SR = true;
        }

        return $SR;
    }

}