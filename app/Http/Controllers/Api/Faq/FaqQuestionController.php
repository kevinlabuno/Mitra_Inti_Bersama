<?php

namespace App\Http\Controllers\Api\Faq;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FaqQuestionController extends Controller
{
    public function GetFaqQuestion(Request $request)
    {
        $searchTerm = $request->input('q', '');
        $customer = auth()->user();


        if (!$customer) {

            try {
                // Mengambil data kategori
                $categories = DB::table('faq_category')->get();

                $formattedData = [];

                foreach ($categories as $category) {
                    // Mendapatkan pertanyaan yang terkait dengan kategori ini dan melakukan pencarian
                    $questionsQuery = DB::table('faq_question')
                        ->where('id_category_faq', $category->id)
                        ->select('judul', 'question', 'id')
                        ->orderBy('created_at', 'desc');

                    // Jika ada parameter pencarian, tambahkan ke query
                    if (!empty($searchTerm)) {
                        $questionsQuery->where(function ($query) use ($searchTerm) {
                            $query->where('judul', 'ILIKE', "%{$searchTerm}%")
                                ->orWhere('question', 'ILIKE', "%{$searchTerm}%");
                        });
                    }

                    // Mendapatkan semua pertanyaan yang sesuai
                    $questions = $questionsQuery->get();

                    // Memeriksa apakah ada pertanyaan untuk kategori ini
                    if ($questions->count() > 0) {
                        $formattedData[] = [
                            'category_faq' => $category->judul,
                            'id'           => $category->id,
                            'questions' => $questions // Mengambil semua item dari query
                        ];
                    }
                }

                return response()->json(
                    [
                        'status'      => true,
                        'status_code' => '00',
                        'data'        => $formattedData,
                    ],
                    200
                );
            } catch (\Exception $e) {
                // Log exception
                Log::error($e);

                // Return an informative response
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        } else {

            try {
                // Mengambil data kategori
                $projectName = strtolower(str_replace(' ', '', $customer['project_name']));

                $categories = DB::table('faq_category')
                    ->whereRaw("REPLACE(LOWER(judul), ' ', '') LIKE ?", ["%{$projectName}%"])
                    ->get();

                $formattedData = [];

                foreach ($categories as $category) {
                    // Mendapatkan pertanyaan yang terkait dengan kategori ini dan melakukan pencarian
                    $questionsQuery = DB::table('faq_question')
                        ->where('id_category_faq', $category->id)
                        ->select('judul', 'question', 'id')
                        ->orderBy('created_at', 'desc');

                    // Jika ada parameter pencarian, tambahkan ke query
                    if (!empty($searchTerm)) {
                        $questionsQuery->where(function ($query) use ($searchTerm) {
                            $query->where('judul', 'ILIKE', "%{$searchTerm}%")
                                ->orWhere('question', 'ILIKE', "%{$searchTerm}%");
                        });
                    }

                    // Mendapatkan semua pertanyaan yang sesuai
                    $questions = $questionsQuery->get();

                    // Memeriksa apakah ada pertanyaan untuk kategori ini
                    if ($questions->count() > 0) {
                        $formattedData[] = [
                            'category_faq' => $category->judul,
                            'id'           => $category->id,
                            'questions' => $questions // Mengambil semua item dari query
                        ];
                    }
                }

                return response()->json(
                    [
                        'status'      => true,
                        'status_code' => '00',
                        'data'        => $formattedData,
                    ],
                    200
                );
            } catch (\Exception $e) {
                // Log exception
                Log::error($e);

                // Return an informative response
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage(),
                ], 500);
            }
        }
    }



    public function CreateFaqQuestion(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'judul'             => 'required',
                'question'          => 'required',
                'id_category_faq'   => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'validation error',
                    'errors'    => $validator->errors()
                ], 442);
            }

            $createDataFaqQuestioon =  DB::table('faq_question')->insert([
                "judul"                => $request->judul,
                "question"             => $request->question,
                "id_category_faq"      => $request->id_category_faq,
                'created_at'           => Carbon::now(),
                'updated_at'           => Carbon::now(),
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


    public function UpdateFaqQuestion(Request $request, $id)
    {
        try {
            $dataFaqQuestion = DB::table('faq_question')->where('id', $id)->first();

            if (!$dataFaqQuestion) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan',
                ], 442);
            }

            // Menggunakan DB::table()->where()->update() untuk melakukan pembaruan
            DB::table('faq_question')->where('id', $id)->update([
                "judul"            => $request->judul ?? $dataFaqQuestion->judul,
                "question"         => $request->question ?? $dataFaqQuestion->question,
                'id_category_faq'  => $request->id_category_faq ?? $dataFaqQuestion->id_category_faq,
                'updated_at'       => Carbon::now(),
            ]);

            return response()->json(
                [
                    'status'      => true,
                    'status_code' => '00',
                    'message'     => "Data berhasil diupdate",
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


    public function GetFaqQuestionById(Request $request, $id)
    {

        try {
            $dataFaqQuestion =  DB::table('faq_question')->where('id', $id)->first();


            if (!$dataFaqQuestion) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan',
                ], 500);
            }

            return response()->json(
                [
                    'status'          => true,
                    'status_code'     => '00',
                    'response'        => $dataFaqQuestion
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


    public function DeleteFaqQuestion($id)
    {

        try {
            $dataFaqQuestionById = DB::table('faq_question')->where('id', $id)->first();

            if (!$dataFaqQuestionById) {

                return response()->json([
                    'status'    => false,
                    'message'   => 'Data tidak ditemukan',
                ], 500);
            } else {

                DB::table('faq_question')->where('id', $id)->delete();

                return response()->json(
                    [
                        'status'         => true,
                        'status_code'    => '00',
                        'message'        => "Data berhasil dihapus",
                        'data'           => $dataFaqQuestionById
                    ],
                    200
                );
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
