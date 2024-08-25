<?php

namespace App\Http\Controllers\Api\MasterData\Kategori;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class KategoriTiket extends Controller
{
    public function getDataKategoriTiket(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        $dataKategori = DB::table('kategori_tiket')->paginate($per_page);

        return response()->json(
            [
                'response' => $dataKategori
            ],
            200
        );
    }

    public function addDataKategoriTiket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_kategori' => 'required|string|max:255|unique:kategori_tiket,jenis_kategori',
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

        $dataKategori = [
            'jenis_kategori' => $request->jenis_kategori,
            'deskripsi' => $request->deskripsi,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $result = DB::table('kategori_tiket')->insert($dataKategori);

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data Kategori Tiket Berhasil Ditambahkan',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data Kategori Tiket Gagal Ditambahkan',
                ],
                400
            );
        }
    }

    public function updateDataKategoriTiket(Request $request, $id)
    {
        $dataKategoriTiket = DB::table('kategori_tiket')->where('id', $id)->first();

        if (!$dataKategoriTiket) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'jenis_kategori' => 'required|string|max:255|unique:kategori_tiket,jenis_kategori,' . $id,
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

        DB::table('kategori_tiket')->where('id' , $id)->update([
            'jenis_kategori' => $request->jenis_kategori,
            'deskripsi' => $request->deskripsi,
            'updated_at' => Carbon::now(),
        ]);

        return response()->json(
            [
                'status' => true,
                'message' => "Data berhasil diupdate",
            ],
            200
        );
    }

    public function getDataKategoriTiketById($id)
    {
        $dataKategori = DB::table('kategori_tiket')->where('id', $id)->first();

        if (!$dataKategori) {
            return response()->json([
                'status'  => false,
                'message' => 'Data tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'data' => $dataKategori,
        ], 200);
    }

    public function deleteDataKategoriTiket($id)
    {
        // Menghapus data berdasarkan id
        $result = DB::table('kategori_tiket')->where('id', $id)->delete();

        if ($result) {
            return response()->json(
                [
                    'response' => 'Data Kategori Tiket Berhasil Dihapus',
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'response' => 'Data Kategori Tiket Gagal Dihapus',
                ],
                400
            );
        }
    }
}
