<?php

namespace App\Http\Controllers\Api\MasterData\Status;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StatusController extends Controller
{
    public function getDataStatus(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        $dataStatus = DB::table('status')->paginate($per_page);

        return response()->json(
            [
                'status'   => true,
                'message'  => 'Success',
                'response' => $dataStatus
            ],
            200
        );
    }

    public function addDataStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'        => 'required|string|max:255|unique:status,status',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors(),
                ],
                400
            );
        }

        $status = [
            'status'     => $request->status,
        ];

        $result = DB::table('status')->insert($status);

        if ($result) {
            return response()->json(
                [
                    'status'   => true,
                    'message'  => 'Data Status, Berhasil Ditambahkan',
                ],
                201
            );
        } else {
            return response()->json(
                [
                    'status'   => false,
                    'message' => 'Data Status, Gagal Ditambahkan',
                ],
                400
            );
        }
    }

    public function updateDataSatus(Request $request, $id)
    {
        $dataStatusById = DB::table('status')->where('id', $id)->first();


        if (!$dataStatusById) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak ditemukan',
            ], 404);
        }


        DB::table('status')->where('id', $id)->update([
            'status'     => $request->status ?? $dataStatusById->status,
        ]);

        return response()->json(
            [
                'status'  => true,
                'message' => "Data Status, berhasil diupdate",
            ],
            201
        );
    }

    public function getDataSetatusById($id)
    {
        $dataStatusById = DB::table('status')->where('id', $id)->first();

        if (!$dataStatusById) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status'   => true,
            'response' => $dataStatusById,
        ], 200);
    }

    public function deleteDataStatus($id)
    {
        $result = DB::table('status')->where('id', $id)->delete();

        if ($result) {
            return response()->json(
                [
                    'status'   => true,
                    'response' => 'Data, Berhasil Dihapus',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'status'   => false,
                    'response' => 'Data Severity Gagal Dihapus',
                ],
                400
            );
        }
    }
}
