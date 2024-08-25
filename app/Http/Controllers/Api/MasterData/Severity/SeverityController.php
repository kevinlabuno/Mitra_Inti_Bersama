<?php

namespace App\Http\Controllers\Api\MasterData\Severity;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SeverityController extends Controller
{
    public function getDataSeverity(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        $dataSeverity = DB::table('tipe_severity')->paginate($per_page);

        return response()->json(
            [
                'response' => $dataSeverity
            ],
            200
        );
    }

    public function addDataSeverity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255|unique:tipe_severity,type',
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

        $dataSeverity = [
            'type' => $request->type,
            'deskripsi' => $request->deskripsi,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $result = DB::table('tipe_severity')->insert($dataSeverity);

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data Severity Berhasil Ditambahkan',
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data Severity Gagal Ditambahkan',
                ],
                400
            );
        }
    }

    public function updateDataSeverity(Request $request, $id)
    {
        $dataSeverity = DB::table('tipe_severity')->where('id', $id)->first();

        if (!$dataSeverity) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'type' => 'required|string|max:255|unique:tipe_severity,type,' . $id,
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

        DB::table('tipe_severity')->where('id' , $id)->update([
            'type' => $request->type,
            'deskripsi' => $request->deskripsi,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => "Data berhasil diupdate",
            ],
            201
        );
    }

    public function getDataSeverityById($id)
    {
        $dataSeverity = DB::table('tipe_severity')->where('id', $id)->first();

        if (!$dataSeverity) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => $dataSeverity,
        ], 200);
    }

    public function deleteDataSeverity($id)
    {
        $result = DB::table('tipe_severity')->where('id', $id)->delete();

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data Severity Berhasil Dihapus',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data Severity Gagal Dihapus',
                ],
                400
            );
        }
    }
}
