<?php

namespace UM\User;


class Logout
{

    /**
     * 
     * current user logout
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function logout()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if( isset($_SESSION['UM_login']) ){
            $_SESSION['UM_login'] = NULL;
        }

        if( isset($_COOKIE['UM_login']) ){
            setcookie('UM_login', '' , (time()-((3600*24*30*13*100))), '/'  );
        }
    }
}