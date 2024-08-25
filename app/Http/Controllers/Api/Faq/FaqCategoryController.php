<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqCategoryController extends Controller
{

    public function GetFaqCategory(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        try {
            $dataFaqCategory =  DB::table('faq_category')->orderBy('created_at', 'desc')->paginate($per_page);

            return response()->json(
                [
                    'status'     => true,
                    'response'   => $dataFaqCategory
                ],
                200
            );
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

    public function GetFaqCategoryName(Request $request)
    {
        try {
            $dataFaqCategoryName =  DB::table('faq_category')->select('id', 'name')->get();

            return response()->json(
                [
                    'status'     => true,
                    'response'   => $dataFaqCategoryName
                ],
                200
            );
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


    public function CreateFaqCategory(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'judul'        => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'validation error',
                    'errors'    => $validator->errors()
                ], 442);
            }

            $createDataFaqCategory =  DB::table('faq_category')->insert([
                "judul"         => $request->judul,
                "deskripsi"     => $request->deskripsi,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            return response()->json(
                [
                    'status'        => true,
                    'status_code'   => '00',
                    'message'       => "Data berhasil ditambahkan",
                ],
                200
            );
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


    public function UpdateFaqCategory(Request $request, $id)
    {
        try {

            $validator = Validator::make($request->all(), [
                'judul'        => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'validation error',
                    'errors'    => $validator->errors()
                ], 442);
            }

            $dataFaqCategory = DB::table('faq_category')->where('id', $id);

            if (!$dataFaqCategory) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Data tidak ditemukan',
                ], 442);
            }

            $dataFaqCategory->update([
                "judul"         => $request->judul,
                "deskripsi"     => $request->deskripsi,
                'updated_at'    => Carbon::now(),
            ]);

            return response()->json(
                [
                    'status'         => true,
                    'status_code'    => '00',
                    'message'       => "Data berhasil diupdate",
                ],
                200
            );
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

    public function GetFaqCategoryById(Request $request, $id)
    {

        try {
            $dataFaqCategoryById =  DB::table('faq_category')->where('id', $id)->first();


            if (!$dataFaqCategoryById) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan',
                ], 500);
            }

            return response()->json(
                [
                    'status'     => true,
                    'response'   => $dataFaqCategoryById
                ],
                200
            );
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


    public function DeleteFaqCategory($id)
    {

        try {
            $dataFaqCategoryById = DB::table('faq_category')->where('id', $id)->first();

            if (!$dataFaqCategoryById) {

                return response()->json([
                    'status'    => false,
                    'message'   => 'Data tidak ditemukan',
                ], 500);
            } else {

                DB::table('faq_category')->where('id', $id)->delete();
                return response()->json(
                    [
                        'status'         => true,
                        'status_code'    => '00',
                        'message'        => "Data berhasil dihapus",
                        'data'           => $dataFaqCategoryById
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
