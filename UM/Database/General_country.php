<?php

namespace UM\Database;

use Illuminate\Support\Facades\DB;




class General_country
{
    /**
     * get all the data of all countries
     * 
     * 
     * @return array all data of all countries
     * 
     * @version 0.1.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function all()
    {
        return DB::select('select * from UM_general_country ORDER BY country_name ASC;');
    }


    /**
     * 
     * get country_name by letters_2
     * 
     * @param string $letters_2
     * 
     * @return string $country_name
     * 
     * @version 0.1.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function country_name( string $letters_2 ): string
    {
        $countries = self::all();

        foreach( $countries as $country ){
            if( $country->letters_2==strtoupper($letters_2) ){
                $country_name= $country->country_name;
                break;
            }
        }
        return $country_name;
    }


    /**
     * 
     * get dialing_code by letters_2
     * 
     * @param string $letters_2
     * 
     * @return string $dialing_code
     * 
     * @version 0.1.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function dialing_code( string $letters_2 ): string
    {
        $countries = self::all();

        $dialing_code='+000';
        foreach( $countries as $country ){
            if( $country->letters_2==strtoupper($letters_2) ){
                $dialing_code= json_decode($country->dialing_code)[0];
                break;
            }
        }

        return $dialing_code;
    }



}