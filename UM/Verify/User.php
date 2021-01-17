<?php
namespace UM\Verify;

use UM\Database\Users;

use Illuminate\Support\Facades\DB;



class User{
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


