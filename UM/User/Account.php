<?php
namespace UM\User;

use UM\Verify\User;
use UM\Database\Users;
use Illuminate\Support\Facades\DB;

class Account
{
    /**
     * Delete whole account based on user_id
     * 
     * @param int $user_id
     * 
     * @since   0.0.0
     * @version 0.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function delete( int $user_id )
    {
        DB::delete('DELETE FROM UM_users WHERE `id`=?', [$user_id]);
        DB::delete('DELETE FROM UM_usermeta WHERE `user_id`=?', [$user_id]);
    }




    /**
     * find out current logged in user_id
     * 
     * 
     * @return int user_id -  0  means no logged in user
     *                     - >0  logged in user_id
     * 
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function current_user_id()
    {
        $SR = 0;
        if(!session_id()) session_start();

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


        if( User::user_is_verified( $user_id ) ){
            $db_username        = Users::select( (int) htmlspecialchars(trim($user_id)), 'username' );
            $db_email           = Users::select( (int) htmlspecialchars(trim($user_id)), 'email' );
            $db_password_hashed = Users::select( (int) htmlspecialchars(trim($user_id)), 'password' );

            if( $username==$db_username && $email==$db_email && $password_hashed==$db_password_hashed ){
                $SR = $user_id;
            }
        }

        
        return $SR;
    }
}