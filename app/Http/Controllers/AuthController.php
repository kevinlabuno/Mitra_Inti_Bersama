<?php

namespace App\Http\Controllers;

use App\Mail\SendCredentialCustomer;
use App\Models\InvalidToken;
use App\Models\User;
use App\Service\LoginLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 442);
        }

        $fieldType = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Retrieve user by username or email
        $user = User::where($fieldType, $request->username)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Username or email not found',
            ], 404);
        }

        // Check if the user account is active or no
        if (!$user->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is locked. Please contact support for assistance.'
            ], 401);
        }

        // Check if the password is correct
        if (!Hash::check($request->password, $user->password)) {
            // Increment failed password attempts
            $user->increment('attempt_password');

            // Check if the failed attempts exceed 3
            if ($user->attempt_password >= 3) {
                $user->update(['is_active' => false]);
                return response()->json([
                    'status' => false,
                    'message' => 'Your account is locked. Please contact support for assistance.'
                ], 401);
            }

            return response()->json([
                'status' => false,
                'message' => 'Incorrect password, please try again.'
            ], 401);
        }



        // Attempt to create a token
        if (!$token = auth()->attempt([$fieldType => $request->username, 'password' => $request->password])) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        // Reset failed attempts after successful login
        DB::table('users')
            ->where('username', $request->username)
            ->update(['attempt_password' => 0]);

        InvalidToken::where('token', $request->bearerToken())->delete();

        return $this->respondWithToken($token);
    }



    public function register(Request $request)
    {
        // dd($request->project_name);
        // DB::beginTransaction();
        $validator = Validator::make($request->all(), [
            'username'         => 'required|string|unique:users|max:30',
            'email'            => 'required|unique:users|email',
            'password'         => 'required|min:6',
            'confirm_password' => 'required|same:password',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 442);
        }


        try {

            if ($request->foto_profile) {
                $path = '/uploads/foto';
                if (!File::exists($path)) {
                    File::makeDirectory($path, 0775, true, true);
                }

                $foto_profile = $request->file('foto_profile');
                $extensionFileSelfie = $foto_profile->getClientOriginalExtension();
                $resultFoto_profile = $path . '/' . time() . '-' . Str::random(5) . '.' . $extensionFileSelfie;
                $foto_profile->move(public_path('/uploads/foto'), $resultFoto_profile);

                $user = User::create([
                    'username'        => $request->username,
                    'nama_customer'   => $request->username,
                    'email'           => $request->email,
                    'password'        => Hash::make($request->password),
                    'foto_profile'    => $resultFoto_profile ?? null,
                    'project_name'    => $request->project_name,
                    'is_active'       => true,
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            } else {
                $user = User::create([
                    'username'        => $request->username,
                    'nama_customer'   => $request->username,
                    'email'           => $request->email,
                    'password'        => Hash::make($request->password),
                    'project_name'    => $request->project_name,
                    'is_active'       => true,
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                ]);
            }

            DB::commit();

            // send email
            $crendential = [
                "username" => $request->username,
                "password" => $request->password,
                "email"    => $request->email,
            ];

            // Mail::to($request->email)->send(new SendCredentialCustomer($crendential));


            return response()->json([
                'status'  => true,
                'message' => 'Data Customer, Berhasil ditambahkan',
                "results" => $user
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json($th->getMessage());
        }
    }



    public function me()
    {
        $user = User::find(auth()->user()->id);

        return response()->json($user);
    }


    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            InvalidToken::create(['token' => $token]);
        }

        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }



    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }


    protected function respondWithToken($token)
    {
        // $user = User::find(auth()->user()->id);

        $data = User::where('email', auth()->user()->username)
            ->orWhere('username', auth()->user()->username)
            ->first(['nama_customer', 'email', 'created_at', 'username', 'customer_id', 'is_active', 'id']);


        return response()->json([
            'status'            => true,
            "message"           => "Success",
            'data'              => $data,
            'token_type'        => 'bearer',
            'access_token'      => $token,
            'expires_in'        => JWTAuth::factory()->getTTL() * 60, // Menit ke detik
            // 'expires_in'        => auth()->factory()->getTTL() * 60,
        ]);
    }
}
