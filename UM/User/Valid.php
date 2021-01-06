<?php
namespace UM\User;


use UM\Database\Options;


class Valid
{
    public static function username()
    {
        
    }


    public static function email()
    {

    }


    public static function password()
    {

    }

    


    /**
     * In Google Recaptcha v2,
     * Check both captcha value matches or not.
     * 
     * @param string $response_id
     * 
     * @return bool  true  - if matches
     *               false - if not matches
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function captcha_google_recaptcha_v2( string $response_id )
    {
        $keys = Options::get('captcha_google_recaptcha_v2');
        $secret_key = $keys['secret_key'];

        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response_id}";

        $url = file_get_contents($url);
        $url = json_decode($url);

        if( $url->success==false ){
            return false;
        }elseif( $url->success==true ){
            return true;
        }
    }
}