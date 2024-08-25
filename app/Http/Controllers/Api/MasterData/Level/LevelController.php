<?php

namespace App\Http\Controllers\Api\MasterData\Level;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LevelController extends Controller
{
    public function getDataLevel(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        $dataLevel = DB::table('level')->paginate($per_page);

        return response()->json(
            [
                'response' => $dataLevel
            ],
            200
        );
    }

    public function addDataLevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_level' => 'required|string|max:255|unique:level,jenis_level',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $dataLevel = [
            'jenis_level' => $request->jenis_level,
            'deskripsi' => $request->deskripsi,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $result = DB::table('level')->insert($dataLevel);

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data Level Berhasil Ditambahkan',
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data Level Gagal Ditambahkan',
                ],
                400
            );
        }
    }

    public function updateDataLevel(Request $request, $id)
    {
        $dataLevel = DB::table('level')->where('id', $id)->first();

        if (!$dataLevel) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jenis_level' => 'required|string|max:255|unique:level,jenis_level,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        DB::table('level')->where('id' , $id)->update([
            'jenis_level' => $request->jenis_level,
            'deskripsi' => $request->deskripsi,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => "Data Level Berhasil diupdate",
            ],
            201
        );
    }

    public function getDataLevelById($id)
    {
        $dataLevel = DB::table('level')->where('id', $id)->first();

        if (!$dataLevel) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => $dataLevel,
        ], 200);
    }

    public function deleteDataLevel($id)
    {
        $result = DB::table('level')->where('id', $id)->delete();

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data level Berhasil Dihapus',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data level Gagal Dihapus',
                ],
                400
            );
        }
    }
}
