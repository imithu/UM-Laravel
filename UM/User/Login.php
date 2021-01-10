<?php

namespace UM\User;


use UM\Database\Users;
use UM\Database\Usermeta;



class Login
{
    /**
     * 
     * login by username or email and password
     * 
     * @param array $config
     * @param array $primary
     * 
     * @return bool true  - login        successful
     *              false - login is not successful
     * 
     * @version 0.1.2
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function login( $config, $primary )
    {
        
        if( $config['captcha_google_recaptcha_v2____verify']==true ){
            $captcha_is_verified = Valid::captcha_google_recaptcha_v2( $config['captcha_google_recaptcha_v2____response_id'] );
            if( $captcha_is_verified==false ){
                return false;
            }
        }

        foreach( $primary as $key=>$value ){
            $primary[$key] = htmlspecialchars( $value );
        }


        $user_id = Users::id_username( $primary['username_or_email'] );
        if( $user_id==false ){
            $user_id = Users::id_email( $primary['username_or_email'] );
        }

        if( $user_id==false ){
            return false;
        }elseif( $user_id==true ){
                $email_is_verified = Usermeta::get( $user_id, 'email_is_verified' );
                if( $email_is_verified=='yes' ){
                    $email_is_verified = true;
                }elseif( $email_is_verified=='no' ){
                    $email_is_verified = false;
                }
                
                if( $email_is_verified==false ){
                    return false;
                }elseif( $email_is_verified==true ){
                        $db_password_hashed = Users::password_hashed_get( $user_id );
                        $password_matches = password_verify( $primary['password'], $db_password_hashed ); // bool
                        if( $password_matches==false ){
                            return false;
                        }elseif( $password_matches==true ){
                            if( $primary['remember_me']=='true' ){
                                $remember_me = true;
                            }elseif( $primary['remember_me']=='false' ){
                                $remember_me = false;
                            }
                            
                            $UM_login = [
                                'user_id'         => $user_id,
                                'username'        => Users::username_id( $user_id ),
                                'email'           => Users::email_id( $user_id ),
                                'password_hashed' => $db_password_hashed
                            ];
                            $UM_login = json_encode($UM_login);

                            if( $remember_me==true ){
                                setcookie('UM_login', $UM_login , (time()+(3600*24*30*13*100)), '/'  );
                            }elseif( $remember_me==false ){
                                if (session_status() == PHP_SESSION_NONE) {
                                    session_start();
                                }
                                $_SESSION['UM_login'] = $UM_login;
                            }

                            return true;
                        }
                        
                }
        }

        return false;
    }
}