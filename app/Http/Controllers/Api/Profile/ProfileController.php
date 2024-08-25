<?php

namespace App\Http\Controllers\Api\Profile;

use App\Http\Controllers\Controller;
use App\Service\UserLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ProfileUser()
    {
        try {

            $user = Auth::user();

            if ($user) {
                $profile = [
                    'nama_lengkap'   => $user->nama_lengkap,
                    'username'       => $user->username,
                    'email'          => $user->email,
                    'nomor_telepon'  => $user->nomer_tlp,
                    'alamat'         => $user->alamat,
                    'foto_profile'   => $user->foto_profile,
                    'kota'           => $user->kota,
                    'nik'           => $user->nik,

                ];

                $dataMerchant = [
                    'nama_merchant'   => $user->namaMerchant,
                    'alamat_toko'   => $user->alamat_toko,
                ];

                $dataKerabat = [
                    'namaKerabat'   => $user->nama_kerabat,
                    'alamat_kerabat'   => $user->alamat_kerabat,
                    'status_kerabat'   => $user->status_kerabat,
                    'nomer_tlp_kerabat'   => $user->nomer_tlp_kerabat,
                ];

                // save user log
                $data = [
                    "username"      => auth()->user()->username,
                    "id_user"       => auth()->user()->user_id,
                    'message'       => 'Berhasil akses Profile'
                ];
                UserLog::saveUserLog($data, 1);

                return response()->json([
                    'status' => true,
                    'response' => [
                        "profile" => $profile,
                        "dataKerabat" => $dataKerabat,
                        "dataMerchant" => $dataMerchant,
                    ]
                ]);
            }
            //save user log
            $data = [
                "username"      => auth()->user()->username,
                "id_user"       => auth()->user()->id,
                'message'       => 'gagal akses Profile'
            ];
            UserLog::saveUserLog($data, 0);
            return response()->json(['error' => 'Unauthenticate'], 401);
        } catch (\Exception $e) {
            // Log exception
            Log::error($e);

            // Return an informative response
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
