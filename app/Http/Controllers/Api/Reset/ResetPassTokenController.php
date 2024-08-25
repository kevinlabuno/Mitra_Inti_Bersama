<?php

namespace App\Http\Controllers\Api\Reset;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;
use Symfony\Contracts\Service\Attribute\Required;

class ResetPassTokenController extends Controller
{
    public function tokenPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([   
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validator->errors()
            ], 442);
        }

        $data = DB::table('reset_password_or_pin')->where('email', $request->email);

        if(!empty($data))
        {
            DB::table('reset_password_or_pin')->where('email', $request->email)->delete();
        }

        $token = mt_rand(100000, 999999);

        DB::table('reset_password_or_pin')->insert(
            [
                'email' => $request->email,
                'token' => $token,
                'action' => 1,
                'status' => 1,
                'message'=> 'code reset has been sent',
                'data' => json_encode($request, $token),
            ]
        );

        Mail::to($request->email)->send(new ResetPassNotification($token));

        $response = [
            'success' => true,
            'status' => 200,
            'message' => 'code reset has been sent'
        ];
        return response($response, 200); 
    }
}
