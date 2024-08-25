<?php
namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Service\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class UpdateProfileController extends Controller
{

    public function updateProfileUser(Request $request)
    {
       


        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            // 'nama_lengkap' => 'required|string|max:30',
            // 'nomer_tlp' => 'required',
            // 'alamat' => 'required',
            // 'nik' => 'required',
            // 'nama_kerabat' => 'required',
            // 'nomer_tlp_kerabat' => 'required',
            // 'alamat_kerabat' => 'required',
            // 'status_kerabat' => 'required',
            // 'nama_merchant' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 442);
        }

        $data = DB::table('users')->where('user_id', $request->user_id)->first();
        $user_id = $request->user_id;


        if (!$data) {

            $response = [
                'status'        => false,
                'message'       => 'Upss data tidak ditemukan!',
                'response'      => $data
            ];

            // save log
            $data = [
                "username"      => auth()->user()->username,
                "id_user"       => auth()->user()->user_id,
                'message'       => 'request update profile',
                'status_code'   => '400',
                'response'      => $response
            ];
            UserLog::saveUserLog($data, 2);

            return response()->json($response, 400);


        } else {
            if ($request->foto_profile) {

                $path = '/uploads/foto';
    
                $foto_profile = $request->file('foto_profile');
                $extensionFileSelfie = $foto_profile->getClientOriginalExtension();
                $resultFoto_profile = $path . '/' . time() . '-' . Str::random(5) . '.' . $extensionFileSelfie;
    
                $foto_profile->move(public_path('/uploads/foto'), $resultFoto_profile);
    
    
                DB::table('users')
                    ->where('user_id', $user_id)
                    ->update([
                        'nama_lengkap'     => $request->nama_lengkap ?? $data->nama_lengkap,
                        'username'         => $request->username ?? $data->username,
                        'email'            => $request->email ?? $data->email,
                        'nomer_tlp'        => $request->nomer_tlp ?? $data->nomer_tlp,
                        'alamat'           => $request->alamat ?? $data->alamat,
                        'nama_kerabat'     => $request->nama_kerabat ?? $data->nama_kerabat,
                        'nomer_tlp_kerabat'=> $request->nomer_tlp_kerabat ?? $data->nomer_tlp_kerabat,
                        'alamat_kerabat'   => $request->alamat_kerabat ?? $data->alamat_kerabat,
                        'status_kerabat'   => $request->status_kerabat ?? $data->status_kerabat,
                        'nik'              => $request->nik ?? $data->nik,
                        'foto_profile'     => $resultFoto_profile ?? $data->foto_profile ?? '-',
                        'namaMerchant'     => $request->namaMerchant ?? $data->namaMerchant,
                        'kota'             => $request->kota ?? $data->kota,
                        'alamat_toko'      => $request->alamat_toko ?? $data->alamat_toko
                    ]);
    
                    $response = [
                        'status'  => true,
                        'message' => 'Data berhasil diupdate'
                    ];
        
                    // save log
                    $data = [
                        "username"      => auth()->user()->username,
                        "id_user"       => auth()->user()->user_id,
                        'message'       => 'request update profile',
                        'status_code'   => '200',
                        'response'      => $response
                    ];
                    UserLog::saveUserLog($data, 1);

                    return response()->json($response, 200);
    
            } else {
    
                DB::table('users')
                ->where('user_id', $user_id)
                ->update([
                    'nama_lengkap'         => $request->nama_lengkap ?? $data->nama_lengkap,
                    'username'             => $request->username ?? $data->username,
                    'email'                => $request->email ?? $data->email,
                    'nomer_tlp'            => $request->nomer_tlp ?? $data->nomer_tlp,
                    'alamat'               => $request->alamat ?? $data->alamat,
                    'nama_kerabat'         => $request->nama_kerabat ?? $data->nama_kerabat,
                    'nomer_tlp_kerabat'    => $request->nomer_tlp_kerabat ?? $data->nomer_tlp_kerabat,
                    'alamat_kerabat'       => $request->alamat_kerabat ?? $data->alamat_kerabat,
                    'status_kerabat'       => $request->status_kerabat ?? $data->status_kerabat,
                    'nik'                  => $request->nik ?? $data->nik,
                    // 'foto_profile'         => $resultFoto_profile ?? $data->foto_profile ?? '-',
                    'namaMerchant'         => $request->namaMerchant ?? $data->namaMerchant,
                    'kota'                 => $request->kota ?? $data->kota,
                    'alamat_toko'          => $request->alamat_toko ?? $data->alamat_toko
                ]);
    
    
                $response = [
                    'status'  => true,
                    'message' => 'Data berhasil diupdate'
                ];
    
                // save log
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'request update profile',
                    'status_code'   => '200',
                    'response'      => $response
                ];
                UserLog::saveUserLog($data, 1);

                return response()->json($response, 200);
            }
        }

    }


    // public function updateProfileUser(Request $request, $id)
    // {
    //     $data = User::find($id);

    //     $validator = Validator::make($request->all(), [
    //         'nama_lengkap' => 'required|string|max:30',
    //         'nomer_tlp' => 'required',
    //         'alamat' => 'required',
    //         'nik' => 'required',
    //         'nama_kerabat' => 'required',
    //         'nomer_tlp_kerabat' => 'required',
    //         'alamat_kerabat' => 'required',
    //         'status_kerabat' => 'required',
    //         'nama_merchant' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'validation error',
    //             'errors' => $validator->errors()
    //         ], 442);
    //     }

    //     if ($request->hasFile('foto_profile')) {
    //         $request->validate([
    //             'foto_profile' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    //         ]);

    //         $path = "/uploads/foto";
    //         $file = $request->file('foto_profile');
    //         $extention = $file->getClientOriginalExtension();
    //         $filename = $path . '/' . time() . '-' . Str::random(5) . '.' . $extention;
    //         $file->move(public_path('uploads/foto'), $filename);

    //         $data['foto_profile'] = $filename;
    //     }

    //     $data->update([
    //         'nama_lengkap' => $request->nama_lengkap,
    //         'nomer_tlp' => $request->nomer_tlp,
    //         'alamat' => $request->alamat,
    //         'nik' => $request->nik,
    //         'nama_kerabat' => $request->nama_kerabat,
    //         'nomer_tlp_kerabat' => $request->nomer_tlp_kerabat,
    //         'alamat_kerabat' => $request->alamat_kerabat,
    //         'status_kerabat' => $request->status_kerabat,
    //         'nama_merchant' => $request->nama_merchant
    //     ]);

        
    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Profile has been updated',
    //         'data' => $data,
    //     ], 200);


    // }
}