<?php

namespace UM\User;

use UM\Database\Users;
use UM\User\Account;
use UM\Verify\User;
use UM\Verify\Syntax;
use UM\Generate\Unknown_Data;

use Illuminate\Support\Facades\DB;




class Register
{


    /**
     * register a user
     * 
     * @param string $username
     * @param string $email
     * @param string $password
     * 
     * @param string $usertype
     * @param string $userstatus
     * 
     * 
     * @return array $SR - i.  [ 'error'=>true,  'username_error'=>(bool), 'email_error'=>(bool) ]
     *                     ii. [ 'error'=>false, 'temp_otp'=>'value', 'user_id'=>value ]
     * 
     * 
     * @since   1.0.0
     * @version 1.2.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function register_main( string $username, string $email, string $password, string $usertype, string $userstatus )
    {
        /**
         * check any account exist or not based on username and email
         * also check username syntax is valid or not
         * and take action
         * 
         * @param string $username
         * @param string $email
         * 
         * 
         * @return array $SR [ $username_error=>(bool),    $email_error=>(bool) ]
         * 
         * @since   1.0.0
         * @version 1.2.0
         * @author  Mahmudul Hasan Mithu
         */
        $account_check = function( string $username, string $email )
        {
            $username_error = false;
            $email_error = false;
            


            // check username syntax is okay or not
            if(Syntax::username($username)){
                // check username exist or not in database
                $user_id = Users::id_username( $username );
                if( $user_id>0 ){
                    if( User::user_is_verified( $user_id ) ){
                        $username_error = true;
                    }else{
                        Account::delete( $user_id );
                    }
                }
            }else{
                $username_error = true;
            }
            

            // check email exist or not in database
            $user_id = Users::id_email( $email );
            if( $user_id>0 ){
                if( User::user_is_verified( $user_id ) ){
                    $email_error = true;
                }else{
                    Account::delete( $user_id );
                }
            }
            


            $SR = 
            [
                'username_error' => $username_error,
                'email_error' => $email_error
            ];
            return $SR;
        };


        /**
         * create account
         * 
         * @param string $username
         * @param string $email
         * @param string $password
         * 
         * @param string $usertype
         * @param string $userstatus
         * 
         * @return array $SR [ $temp_otp=>'value',    $user_id=>value ]
         * 
         * @since   1.0.0
         * @version 1.2.0
         * @author  Mahmudul Hasan Mithu
         */
        $account_create = function( string $username, string $email, string $password, string $usertype, string $userstatus  )
        {
            date_default_timezone_set('UTC');
            $password = password_hash( $password, PASSWORD_DEFAULT );
            $temp_otp = Unknown_Data::random_name();
            $datetime = date('Y-m-d H:i:s'); 

            DB::insert
            ( 
                'INSERT INTO UM_users (`id`, `username`, `email`, `password`, `usertype`, `userstatus`, `email_is_verified`, `temp_otp`, `datetime` ) VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?) ',
                                //    [ $username, $email,  $password,   $usertype,  $userstatus,         'no',         $temp_otp,  $datetime ] 
                                   [ htmlspecialchars($username), htmlspecialchars($email),  $password,   $usertype,  $userstatus,         'no',         $temp_otp,  $datetime ]
            );

            $user_id = Users::id_username( $username );


            $SR = 
            [
                'temp_otp'=> $temp_otp,
                'user_id'=> $user_id
            ];
            return $SR;
            
        };




        $SR = 
        [
            'error' => true,
            'username_error'=> true,
            'email_error'=>true
        ];

        // removed side space except password
        $username = strtolower(trim($username));    // convert the username to lowercase
        $email = strtolower(trim($email));          // convert the email    to lowercase
        $usertype = trim($usertype);
        $userstatus = trim($userstatus);

        $SR_temp = $account_check( $username, $email );

        if( $SR_temp['username_error']==true || $SR_temp['email_error']==true ){
            $SR =
            [
                'error' => true,
                'username_error'=> $SR_temp['username_error'],
                'email_error'=>$SR_temp['email_error']
            ];
            return $SR;
        }

        $SR_temp = $account_create( $username, $email, $password, $usertype, $userstatus );

        $SR = 
        [
            'error' => false,
            'temp_otp'=> $SR_temp['temp_otp'],
            'user_id'=> $SR_temp['user_id']
        ];


        return $SR;
    }
}