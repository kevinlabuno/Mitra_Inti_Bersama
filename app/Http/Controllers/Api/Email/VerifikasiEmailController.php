<?php

namespace App\Http\Controllers\Api\Email;

use App\Http\Controllers\Controller;
use App\Mail\EmailNotification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class VerifikasiEmailController extends Controller
{
    public function verifikasiEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'validation error',
                'errors'  => $validator->errors()
            ], 442);
        }

        try {
            $code_verify = substr(time() * rand(), 0, 6);
            DB::table('verifikasi_email')->updateOrInsert([
                "email" => $request->email
            ], [
                'email'      => $request->email,
                'code'       => $code_verify,
                'expired_at' => Carbon::now()->addMinute(5),
                'created_at' => Carbon::now()
            ]);

            $dataToMail = ['code' => $code_verify, 'name' => $request->email];
            Mail::to($request->email)->send(new EmailNotification($dataToMail));

            return response()->json(["status" => '201', 'message' => 'Costumer successfully created'])->setStatusCode(201);
        
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

    }


    public function verifikasiCodeEmail(Request $request) 
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
        // dd($user);   


        if (!empty($user) && $user->expired_at > Carbon::now()) {
            if ($user->code == $request->code) {
                // $update = User::where('email', $request->email)->update(['email_verified_at' => Carbon::now()]);
                // $user_id = User::where('email', $request->email)->first(['id']);
                DB::table('verifikasi_email')->where('email', $request->email)->update([
                    'is_verified' => true
                ]);

                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message'     => 'Verify email success',
                    // 'data' => $user_id->id
                ], 200);
            } else {
                return response()->json([
                    'status'       => false,
                    'status_code'  => 409,
                    'message'      => 'invalid code or email already registered',
                ]);
            }
        } else {
            return response()->json([
                'status'      => false,
                'status_code' => 419,
                'message'     => 'email not found'
            ]);
        }

    }

}
