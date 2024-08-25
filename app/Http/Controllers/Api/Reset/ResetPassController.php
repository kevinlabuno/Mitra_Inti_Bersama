<?php

namespace App\Http\Controllers\API\Reset;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\UserLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class ResetPassController extends Controller
{
    public function resetPass(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|exists:reset_password_or_pin,token',
            'newPassword' => 'required|string|min:6',
            'confirmNewPassword' => 'same:newPassword'
        ]);

        if ($validator->fails()) {
            return response()->json([   
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validator->errors()
            ], 442);
        }

        $pwReset = DB::table('reset_password_or_pin')->where('token', $request->code)->first();

        if($pwReset->created_at > Carbon::now()->addHour())
        {
            DB::table('reset_password_or_pin')->where('token', $request->code)->delete();

            $response = [
                'success' => false,
                'status' => 422,
                'message' => 'Token Expired'
            ];
            return response($response, 422);
        }

        $user = User::firstWhere('email', $pwReset->email);

        $user->update([
            'password' => Hash::make($request->newPassword),
        ]);

        DB::table('reset_password_or_pin')->where('token', $request->code)->delete();

        $response = [
            'success' => true,
            'status' => 200,
            'message' => 'password has been successfully reset'
        ];

        return response($response, 200);
    }
}
