<?php

namespace UM\User;


use UM\Database\Users;
use UM\Verify\User;



class Login
{
    /**
     * login a user
     * 
     * @param string $username_or_email
     * @param string $password
     * @param string $remember_me   'true | false'
     * @param string $usertype
     * 
     * @return boolean - true  - login successful
     *                   false - login failed
     * 
     * @since   1.0.0
     * @version 1.3.1
     * @author  Mahmudul Hasan Mithu
     */
    public static function login_main( $username_or_email, $password, $remember_me, $usertype )
    {
        $SR = false;

        $username_or_email = strtolower(trim($username_or_email));
        $user_id = Users::id_username_or_email( $username_or_email );
        
        if( 
               $user_id>0
            && User::user_is_verified($user_id) 
            && password_verify($password, Users::select($user_id, 'password'))
            && $usertype==Users::select( $user_id, 'usertype' )
            && 'active' ==Users::select( $user_id, 'userstatus' )
        )
        {
            $UM_login = [
                'user_id'         => $user_id,
                'username'        => Users::select( $user_id, 'username' ),
                'email'           => Users::select( $user_id, 'email' ),
                'password_hashed' => Users::select( $user_id, 'password'),
                'usertype'        => $usertype,
                'userstatus'      => 'active'
            ];
            $UM_login = json_encode($UM_login);

            if( $remember_me=='true' ){
                setcookie('UM_login', $UM_login , (time()+(3600*24*30*13*100)), '/'  );
            }elseif( $remember_me=='false' ){
                if(!session_id()) session_start();
                $_SESSION['UM_login'] = $UM_login;
            }
            $SR = true;
        }


        return $SR;
    }
}