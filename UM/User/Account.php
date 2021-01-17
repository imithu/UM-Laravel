<?php
namespace UM\User;

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
}