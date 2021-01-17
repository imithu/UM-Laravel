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
         * @return array $SR [ $username_error=>true|false,    $email_error=>true|false ]
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
     * Register a user
     * 
     * 
     * @param array $config
     * @param array $primary
     * @param array $secondary
     * 
     * @return array $SR
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function user_register( $config, $primary, $secondary )
    {
        foreach( $primary as $key=>$value ){
            $primary[$key] = htmlspecialchars( $value );
        }

        foreach( $secondary as $key=>$value ){
            $secondary[$key] = htmlspecialchars( $value );
        }

        /** SR */
            $SR_A = [
                'error'         => true,
                'captcha_error' => true  
            ];

            $SR_B1 = [
                'error'          => true,
                'captcha_error'  => false,
            
                'username_exist' => true,
                'email_exist'    => true,
            ];

            $SR_B2 = [
                'error'          => true,
                'captcha_error'  => false,
            
                'username_exist' => true,
                'email_exist'    => false,
            ];

            $SR_B3 = [
                'error'          => true,
                'captcha_error'  => false,
            
                'username_exist' => false,
                'email_exist'    => true,
            ];

            $SR_C = [
                'error'          => false,
                'captcha_error'  => false,
            
                'username_exist' => false,
                'email_exist'    => false,


                'user_id'        => 0,
                'username'       => '',
                'temp_otp'       => ''
            ];
        /** ./SR */

            if( $config['captcha_google_recaptcha_v2____verify']==true ){
                $captcha_is_verified = Valid::captcha_google_recaptcha_v2( $config['captcha_google_recaptcha_v2____response_id'] );
                if( $captcha_is_verified==false ){
                    $SR = $SR_A;
                    return $SR;
                }
            }


            $user_id = Users::id_username( $primary['username'] );
            if( $user_id==0 ){
                $username_exist = false;
            }elseif( $user_id>0 ){
                $user_is_verified = Common::user_is_verified( $user_id );
                if( $user_is_verified==false ){
                    Account::delete( $user_id );
                    $username_exist = false;
                }elseif( $user_is_verified==true ){
                    $username_exist = true;
                }
            }


            $user_id = Users::id_email( $primary['email'] );
            if( $user_id==0 ){
                $email_exist = false;
            }elseif( $user_id>0 ){
                $user_is_verified = Common::user_is_verified( $user_id );
                if( $user_is_verified==false ){
                    Account::delete( $user_id );
                    $email_exist = false;
                }elseif( $user_is_verified==true ){
                    $email_exist = true;
                }
            }


            if( $username_exist==true && $email_exist==true ){
                $SR = $SR_B1;
            }elseif( $username_exist==true && $email_exist==false ){
                $SR = $SR_B2;
            }elseif( $username_exist==false && $email_exist==true ){
                $SR = $SR_B3;
            }elseif( $username_exist==false && $email_exist==false ){
                date_default_timezone_set('UTC');
                $datetime = date('Y-m-d H:i:s');
                if( $primary['email_is_verified']==false ){
                    $temp_otp = Dynamic_Data::random_name();
                    Users::user_add( $primary['username'], $primary['email'], $primary['password'] );
                    $user_id = Users::id_username( $primary['username'] );
                    Usermeta::set( $user_id, 'user_type', $primary['user_type'] );
                    Usermeta::set( $user_id, 'user_status', $primary['user_status'] );
                    Usermeta::set( $user_id, 'email_is_verified', 'no' );
                    Usermeta::set( $user_id, 'ip_register', Common::user_ip() );
                    Usermeta::set( $user_id, 'date_register', $datetime );
                    Usermeta::set( $user_id, 'account_created_by', $primary['account_created_by'] );
                    Usermeta::set( $user_id, 'temp_otp', $temp_otp );
                    // secondary info push to database
                    foreach( $secondary as $meta_key=>$meta_value ){
                        Usermeta::set( $user_id, $meta_key, $meta_value );
                    }


                    $SR_C['user_id']  = $user_id;
                    $SR_C['username'] = $primary['username'];
                    $SR_C['temp_otp'] = $temp_otp;
                }elseif( $primary['email_is_verified']==true ){
                    Users::user_add( $primary['username'], $primary['email'], $primary['password'] );
                    $user_id = Users::id_username( $primary['username'] );
                    Usermeta::set( $user_id, 'user_type', $primary['user_type'] );
                    Usermeta::set( $user_id, 'user_status', $primary['user_status'] );
                    Usermeta::set( $user_id, 'email_is_verified', 'yes' );
                    Usermeta::set( $user_id, 'ip_register', Common::user_ip() );
                    Usermeta::set( $user_id, 'date_register', $datetime );
                    Usermeta::set( $user_id, 'account_created_by', $primary['account_created_by'] );
                    Usermeta::set( $user_id, 'temp_otp', 'no_otp' );
                    // secondary info push to database
                    foreach( $secondary as $meta_key=>$meta_value ){
                        Usermeta::set( $user_id, $meta_key, $meta_value );
                    }
                }
                $SR = $SR_C;
            }


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