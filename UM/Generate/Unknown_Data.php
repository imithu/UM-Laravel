<?php

namespace UM\Generate;


class Unknown_Data
{
    /**
     * Generate random name
     * 
     * mainly for generating random file name
     * 
     * @param string  $user_id   ( optional )
     * @param string  $file_name ( optional )
     * @param string  $salt      ( optional )
     * 
     * @return string random name
     * 
     * 
     * @since   0.0.0
     * @version 1.3.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function random_name( $user_id='', $file_name='', $salt='' )
    {
        /*
            user_id:   92
            salt:      eg. img_profile
            date:      2021011744116
            rand:      346237978
            uniqid:    9fab6dc07d367
            file_name: name.name
        */
        $user_id = htmlspecialchars(trim($user_id));
        $file_name = htmlspecialchars(trim($file_name));
        $salt = htmlspecialchars(trim($salt));

        date_default_timezone_set('UTC');
        $random_name = (string) $user_id.'_'.$salt.date('_YnjGis_').rand(100000000,999000999).'_'.uniqid().'_'.$file_name;
        $random_name = preg_replace('/(\s+)/','_s_', $random_name);
        $random_name = trim($random_name);
        return $random_name;
    }



    
    /**
     * Generate temp_otp
     * 
     * mainly created for generating temp otp
     * 
     * @param int $digit
     * 
     * @return string temp_otp
     * 
     * 
     * @since   0.0.0
     * @version 1.0.0
     * @author  Mahmudul Hasan Mithu
     */
    public static function temp_otp( int $digit )
    {
        $otp='';

        for($i=0; $i<$digit; $i++){
            $otp.=rand(1,9);
        }

        return (string) $otp;
    }
}