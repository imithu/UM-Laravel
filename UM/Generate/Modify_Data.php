<?php

namespace UM\Generate;


class Modify_Data
{
    /**
     * get the age
     * 
     * @param string $birthday  eg.  1515-08-06
     * 
     * @return int age   eg. 505
     * 
     * @version 1.0.0
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
}
