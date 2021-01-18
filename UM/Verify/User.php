<?php
namespace UM\Verify;

use UM\Database\Users;

use Illuminate\Support\Facades\DB;



class User{
    /**
     * verify a user by temp_otp
     * 
     * @param int    $user_id
     * @param string $temp_otp
     * 
     * @return boolean - true  if temp_otp verification is successful
     *                 - false if temp_otp verification is failed
     *  
     * 
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function temp_otp( int $user_id, string $temp_otp)
    {
        $db_temp_otp = Users::select( $user_id, 'temp_otp' );
        if( $db_temp_otp!='no_otp' && $db_temp_otp!='' && $db_temp_otp!=NULL && $db_temp_otp!=false ){
            if( $temp_otp==$db_temp_otp ){
                Users::update( $user_id, 'temp_otp', 'no_otp' );
                return true;
            }
        }
        return false;
    }




    /**
     * check a user is verified or not
     * 
     * @param int $user_id
     * 
     * @return bool - true  - user_id is     verified
     *                false - user_id is not verified
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function user_is_verified( int $user_id )
    {
        if( Users::select( $user_id, 'email_is_verified' )=='yes' ){
            return true;
        }
        return false;
    }
}


