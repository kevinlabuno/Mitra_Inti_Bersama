<?php

namespace App\Http\Controllers\Api\Reset\Pin;

use App\Http\Controllers\Controller;
use App\Mail\ResetPinNotification;
use App\Service\UserLog;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResetPinController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public  function resetPinVerfikasiEmail(Request $request)
    {
        {
            $validator = Validator::make($request->all(), [
                'email'    => 'required',
                // "user_id"  => 'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'validation error',
                    'errors'  => $validator->errors()
                ], 442);
            }
    
            try {

                $data = DB::table('reset_password_or_pin')->where('email', $request->email);

                if(!empty($data))
                {
                    DB::table('reset_password_or_pin')->where('email', $request->email)->delete();
                }

                $token = mt_rand(100000, 999999);

                DB::table('reset_password_or_pin')->insert(
                    [
                        'email'     => $request->email,
                        'token'     => $token,
                        'action'    => "1",
                        'status'    => "1",
                        'message'   => 'code reset has been sent',
                        'data'      => json_encode($request, $token),
                        // "id_usser"  => $request->user_id
                    ]
                );

                Mail::to($request->email)->send(new ResetPinNotification($token));


                // save to log user
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'verifkasi email - reset pin',
                    'status_code'   => '200',
                    'response'      => 'token'. $token
                ];
                UserLog::saveUserLog($data, 1);

                return response()->json([
                    'success' => true,
                    'status' => 200,
                    'message' => 'code reset has been sent'
                ]); 
                
            
            } catch (\Exception $e) {
                return response()->json(['message' => $e->getMessage()], $e->getCode());
            }
    
        }
    }

    public function resetpPinVerifikasiCodeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'   => 'required',
            'email'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validator->errors()
            ], 442);
        }

        $user = DB::table('verifikasi_email')
            ->where('is_verified', 0)
            ->where('email', $request->email)
            ->first();


        try {

            $pwReset = DB::table('reset_password_or_pin')->where('token', $request->code)->first();

            // cek kadaluarasa token
            if($pwReset->created_at > Carbon::now()->addHour())
            {
                DB::table('reset_password_or_pin')->where('token', $request->code)->delete();

                $response = [
                    'success' => false,
                    'status' => 422,
                    'message' => 'Token Expired'
                ];

                // save to log user
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'verifkasi email code token expired - reset pin',
                    'status_code'   => '422',
                    'response'      => $response
                ];
                UserLog::saveUserLog($data, 2);

                return response($response, 422);
            }

            $user = DB::table('reset_password_or_pin')
            ->where('email', $request->email)
            ->first();

            if ($user->token == $request->code) {

                DB::table('reset_password_or_pin')->where('token', $request->code)->delete();

                $response = [
                    'status'      => true,
                    'status_code' => 200,
                    'message'     => 'Verify Code Sukses',
                ];

                // save to log user
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'verifkasi email code sukses - reset pin',
                    'status_code'   => '200',
                    'response'      => $response
                ];
                UserLog::saveUserLog($data, 1);
    
                return response($response, 200);

            } else {

                $response = [
                    'status'       => false,
                    'status_code'  => 409,
                    'message'      => 'invalid code',
                ];

                // save to log user
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'invalid code - reset pin',
                    'status_code'   => '409',
                    'response'      => $response
                ];
                UserLog::saveUserLog($data, 2);

                return response($response, 409);
            }

            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

    }


    public function resetPinUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id'      => 'required',
            'pin'          => 'required|digits_between:6,6|integer',
            'confirm_pin'  => 'required|same:pin',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validator->errors()
            ], 442);
        }

        try {

            $user = DB::table('users')->where('user_id', $request->user_id)->first();
            // Periksa apakah pengguna ditemukan
            if (!$user) {

                $response = [
                    'message'   => 'User not found',
                ];

                // save to log user
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'User not found - reset pin',
                    'status_code'   => '404',
                    'response'      => $response
                ];
                UserLog::saveUserLog($data, 2);

                return response($response, 404);
            }

            // hasPin
            $hashedPin = base64_encode($request->pin);
            // UPDATE PIN
            DB::table('users')->where('user_id', $request->user_id)->update(['pin' => $hashedPin]);

            $response = ['message' => 'PIN updated successfully'];

            // save to log user
            $data = [
                "username"      => auth()->user()->username,
                "id_user"       => auth()->user()->user_id,
                'message'       => 'PIN updated successfully - reset pin',
                'status_code'   => '200',
                'response'      => $response
            ];
            UserLog::saveUserLog($data, 1);
            
            return response($response, 200);

            
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }

}
