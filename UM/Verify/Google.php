<?php
namespace UM\Verify;


use UM\Database\Options;
use Illuminate\Support\Facades\DB;



class Google
{
    /**
     * Check captcha value matches or not from server side
     * 
     * @param string $response_id
     * 
     * @return bool  true  - if     matches
     *               false - if not matches
     * 
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function captcha_recaptcha_v2( string $response_id )
    {
        $SR = false;

        $keys = Options::select('google_captcha_recaptcha_v2');
        $secret_key = $keys['secret_key'];

        $url = "https://www.google.com/recaptcha/api/siteverify?secret={$secret_key}&response={$response_id}";
        $url = file_get_contents($url);
        $url = json_decode($url);

        if( $url->success==true ){
            $SR = true;
        }

        return $SR;
    }    
}