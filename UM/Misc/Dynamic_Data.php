<?php

namespace UM\Misc;


class Dynamic_Data
{ 
    /**
     * get the age
     * 
     * @param string $birthday  eg.  2021-12-27
     * 
     * @return int age   eg. 18
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function age( $birthday )
    {
        date_default_timezone_set('UTC');
        // b - birthday
        // p - present day

        $b = date_create( $birthday );
        $p = date_create( date('Y-m-d') );

        $time = date_diff( $p,$b );

        return (int) $time->y;
    }


    /**
     * Generate random name
     * 
     * mainly created for generating file name
     * 
     * @param string  $user_id   ( optional )
     * @param string  $file_name ( optional )
     * @param string  $salt      ( optional )
     * 
     * @return string random name
     * 
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function random_name( $user_id='', $file_name='', $salt='' )
    {
        /*
            user_id:   92
            salt:      eg. profile_picture_name
            date:      2021101142925
            rand:      246257978
            uniqid:    9fab6dc07d367
            file_name: baby3.jpg
        */
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
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
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