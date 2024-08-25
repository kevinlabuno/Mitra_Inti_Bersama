<?php

namespace App\Http\Controllers\Api\UserLog;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserLogController extends Controller
{
    public function getAllDataUserLog()
    {
        $userLog = DB::table('user_log')->select('*')->get();

        return response()->json($userLog);
    }
}
