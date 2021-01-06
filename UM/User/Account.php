<?php
namespace UM\User;

use Illuminate\Support\Facades\DB;

class Account
{
    /**
     * Delete whole account based on user_id
     * 
     * @param int $user_id
     * 
     * @version 0.0.0
     * @since 0.0.0
     * @author Mahmudul Hasan Mithu
     */
    public static function delete( int $user_id )
    {
        DB::delete('DELETE FROM UM_users WHERE `id`=?', [$user_id]);
        DB::delete('DELETE FROM UM_usermeta WHERE `user_id`=?', [$user_id]);
    }
}