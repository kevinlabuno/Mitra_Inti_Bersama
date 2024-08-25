<?php

namespace App\Service;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class LoginLog
{
    public static function saveLoginLog($request, $status)
    {
        return DB::table('login_log')->insert([
            'username'       => $request['username'],
            'id_user'        => $request['id_user'],
            'status_code'    => $request['status_code'],
            'status'         => $status,
            'message'        => $request['message'],
            'data'           => json_encode($request),
            'created_at'     => Carbon::now()
        ]);
    }
}
