<?php
namespace UM\Verify;

use Illuminate\Support\Facades\DB;

class Syntax
{
    /**
     * check username syntax is valid or not
     * -------------------------------------
     * rules:
     * - minimum 2 characters
     * - no space
     * 
     * 
     * @param string $username
     * 
     * @return bool  true  - if username syntax is correct
     *               false - if username syntax is not correct
     * 
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function username( string $username )
    {
        $username = trim($username);
        if( strlen($username)>1 ){
            preg_match( '/ /', $username, $m );
            if( count($m)==0 ){
                return true;
            }
        }
        return false;
    }




    /**
     * check email syntax is valid or not
     * 
     * 
     * @param string $email
     * 
     * @return bool  true  - if email syntax is correct
     *               false - if email syntax is not correct
     * 
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function email( string $email )
    {
        $email = htmlspecialchars(trim($email));

        preg_match( '/((.+)(@)(.+)(\.)(.+))/', $email, $m );
        if( count($m)>0 ){  // true -  it is an email
            return true;
        }

        return false;
    }
}