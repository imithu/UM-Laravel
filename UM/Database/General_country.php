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
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function all()
    {
        return DB::select('select * from UM_general_country;');
    }


    /**
     * 
     * get country_name by country id
     * 
     * @param int $country_id
     * 
     * @return string $country_name
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function country_name( int $country_id ): string
    {
        $countries = self::all();

        foreach( $countries as $country ){
            if( $country->id==$country_id ){
                $country_name= $country->country_name;
                break;
            }
        }

        return $country_name;
    }


    /**
     * 
     * get dialing_code of a country by country id
     * 
     * @param int $country_id
     * 
     * @return string $dialing_code
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function dialing_code( int $country_id ): string
    {
        $countries = self::all();

        $dialing_code='+000';
        foreach( $countries as $country ){
            if( $country->id==$country_id ){
                $dialing_code= json_decode($country->dialing_code)[0];
                break;
            }
        }

        return $dialing_code;
    }
}