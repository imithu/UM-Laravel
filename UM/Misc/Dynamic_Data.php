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