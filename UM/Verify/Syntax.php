<?php
namespace UM\Verify;


class Syntax
{
    /**
     * check username syntax is valid or not
     * -------------------------------------
     * rules:
     * - minimum 2 characters
     * - no space, ', ", <, >, & character
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
        if( strlen($username)>1 ){
            preg_match( '/([ \'"<>&]+)/', $username, $m );
            if( count($m)==0 ){
                return true;
            }
        }
        return false;
    }




    /**
     * check email syntax is valid or not
     * -----------------------------------
     * rules:
     * - must pass /((.+)(@)(.+)(\.)(.+))/
     * - no space, ', ", <, >, & character
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
        preg_match( '/((.+)(@)(.+)(\.)(.+))/', $email, $m );
        if( count($m)>0 ){  // true -  it is an email
            preg_match( '/([ \'"<>&]+)/', $email, $m );
            if( count($m)==0 ){  // true no space, ', ", <, >, & character
                return true;
            }
        }

        return false;
    }




    /**
     * check password syntax is valid or not
     * -----------------------------------
     * rules:
     * - minimum 8 characters
     * 
     * 
     * @param string $password
     * 
     * @return bool  true  - if password syntax is correct
     *               false - if password syntax is not correct
     * 
     * 
     * 
     * @since   1.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function password( string $password )
    {
        if( strlen($password)>7 ){
            return true;
        }

        return false;
    }
}