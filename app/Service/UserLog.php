<?php

namespace App\Service;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class UserLog
{
    public static function saveUserLog($request, $status)
    {
        return DB::table('user_log')->insert([
            'username'       => $request['username'],
            'id_user'        => $request['id_user'],
            'message'        => $request['message'] ?? $request['keterangan'] ?? '-',
            'status'         => $status,
            'data'           => json_encode($request),
            'created_at'     => Carbon::now()
        ]);
    }
}

