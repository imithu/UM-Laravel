<?php

namespace UM\User;

use UM\Misc\Dynamic_Data;
use UM\User\Common;
use UM\Database\Users;
use UM\Database\Usermeta;


class Password
{

    /**
     * 
     * password reset step 1
     * in step 1,
     * 
     * 
     * @param string username_or_email
     * 
     * @return array   [ user_id, email ,temp_otp ]
     *                 else []
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function password_reset_step_1( $username_or_email )
    {
        $user_id = Users::id_username_or_email( $username_or_email );

        if( $user_id==false ){
            return [];
        }elseif( $user_id>0 ){
            $user_is_verified = Common::user_is_verified( $user_id );
            if( $user_is_verified==false ){
                return [];
            }elseif( $user_is_verified==true ){
                $temp_otp = Dynamic_Data::temp_otp(6);
                $email = Users::email_id( $user_id );

                Usermeta::set( $user_id, 'temp_otp', $temp_otp );

                return [ 'user_id'=>$user_id, 'email'=>$email, 'temp_otp'=>$temp_otp];
            }
        }

        return [];
    }


    /**
     * password reset step 2
     * in step 2,
     * match the temp_otp and do many things 
     * then change the password
     * 
     * @param int $user_id
     * @param string $temp_otp
     * @param string $password
     * 
     * @return bool - true  - password change is successful
     *                false - password change is failed
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function password_reset_step_2( int $user_id, string $temp_otp, string $password )
    {
        if( strlen($password)<8 ){
            return false;
        }elseif( strlen($password)>7 ){
            $user_is_verified = Common::user_is_verified( $user_id );
            if( $user_is_verified==false ){
                return false;
            }elseif( $user_is_verified==true ){
                $db_temp_otp = Usermeta::get( $user_id, 'temp_otp' );
                if( $db_temp_otp=='no_otp' ){
                    return false;
                }elseif( $db_temp_otp!='no_otp' ){
                    if( $temp_otp!=$db_temp_otp ){
                        return false;
                    }elseif( $temp_otp==$db_temp_otp ){
                        Users::password_hashed_set( $user_id, $password );
                        Usermeta::set( $user_id, 'temp_otp', 'no_otp' );
                        return true;
                    }
                }
            }
        }

        return false;
    }

}