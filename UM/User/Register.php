<?php

namespace UM\User;

use UM\Database\Users;
use UM\User\Account;
use UM\Verify\User;
use UM\Generate\Unknown_Data;

use Illuminate\Support\Facades\DB;





use UM\User\Common;
use UM\User\Valid;
use UM\Misc\Dynamic_Data;

use UM\Database\Usermeta;



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
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function register_main( string $username, string $email, string $password, string $usertype, string $userstatus )
    {
        /**
         * check any account exist or not based on username and email
         * and take action
         * 
         * @param string $username
         * @param string $email
         * 
         * 
         * @return array $SR [ $username_error=>(bool),    $email_error=>(bool) ]
         * 
         * @since   1.0.0
         * @version 1.0.0
         * @author  Mahmudul Hasan Mithu
         */
        $account_check = function( string $username, string $email )
        {
            $username_error = false;
            $email_error = false;
            



            $user_id = Users::id_username( $username );
            if( $user_id>0 ){
                if( User::user_is_verified( $user_id ) ){
                    $username_error = true;
                }else{
                    Account::delete( $user_id );
                }
            }

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
         * @version 1.0.0
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
                                   [ $username, $email,  $password,   $usertype,  $userstatus,         'no',         $temp_otp,  $datetime ] 
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
        $username = trim($username);
        $email = trim($email);
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


    /**
     * confirm user account by verifying email
     * 
     * @param int    $user_id
     * @param string $username
     * @param string $temp_otp
     * 
     * @return bool true  - account is     confirmed
     *              false - account is not confirmed
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function verify_email( int $user_id, string $username, string $temp_otp )
    {
        $email_exist = Users::email_id( $user_id );
        ($email_exist==false) ? $email_exist=false : $email_exist=true;

        if( $email_exist==false ){
            return false;
        }elseif( $email_exist==true ){
            $email_is_verified = Usermeta::get( $user_id, 'email_is_verified' );
            if( $email_is_verified=='yes' ){
                $email_is_verified = true;
            }elseif( $email_is_verified=='no' ){
                $email_is_verified = false;
            }
            
            if( $email_is_verified==true ){
                return false;
            }elseif( $email_is_verified==false ){
                $db_username = Users::username_id( $user_id );
                if( $username!=$db_username ){
                    return false;
                }elseif( $username==$db_username ){
                    $db_temp_otp = Usermeta::get( $user_id, 'temp_otp' );
                    if( $temp_otp!=$db_temp_otp ){
                        return false;
                    }elseif( $temp_otp==$db_temp_otp ){
                        Usermeta::set( $user_id, 'email_is_verified', 'yes' );
                        Usermeta::set( $user_id, 'temp_otp', 'no_otp' );
                        Usermeta::set( $user_id, 'user_status', 'active' );
                        return true;
                    }
                }
            }
        }
        
        
        return false;
    }
}