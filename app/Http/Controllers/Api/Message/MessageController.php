<?php

namespace App\Http\Controllers\Api\Message;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;



class MessageController extends Controller
{
    public function getMessageById(Request $request)
    {
        $per_page = empty($request->per_page) ? 20 : $request->per_page;

        try {
            $dataMessage =  DB::table('message_ticket')
                ->where('id_ticket', $request->idTicket)
                ->where('message_ticket.id_customer', $request->idCustomer)
                ->join('users', DB::raw('CAST(users.id AS TEXT)'), '=', 'message_ticket.id_customer')
                ->select(
                    'message_ticket.id',
                    'message_ticket.message',
                    'users.username',
                    'message_ticket.file',
                    'message_ticket.id_level_1_agent',
                    'message_ticket.id_level_2_agent',
                    'message_ticket.id_level_3_agent',
                    'message_ticket.id_level_4_agent',
                    'message_ticket.created_at',
                    'message_ticket.updated_at'
                )
                ->paginate($per_page);


            if (!$dataMessage) {
                return response()->json(
                    [
                        'status'     => false,
                        'message'    => 'Data belum ada!',
                        'response'   => []
                    ],
                    500
                );
            }

            return response()->json(
                [
                    'status'     => true,
                    'response'   => $dataMessage
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

    public function CreateMessage(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'message'              => 'required',
                'id_customer'          => 'required',
                'id_ticket'            => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'validation error',
                    'errors'    => $validator->errors()
                ], 442);
            }

            $message = $request->input('message');


            if ($request->file) {
                $path = '/uploads/file_chat';
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $resultfile = $path . '/' . time() . '-' . Str::random(5) . '.' . $extension;

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0775, true, true);
                }

                $file->move(public_path('/uploads/file_chat'), $resultfile);

                broadcast(new MessageSent($message));

                DB::table('message_ticket')->insert([
                    "message"               => $message,
                    'file'                  => $resultfile,
                    'id_customer'           => $request->id_customer,
                    'id_ticket'             => $request->id_ticket,
                    'id_level_1_agent'      => $request->id_level_1_agent,
                    'id_level_2_agent'      => $request->id_level_2_agent,
                    'id_level_3_agent'      => $request->id_level_3_agent,
                    'id_level_4_agent'      => $request->id_level_4_agent,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);



                return response()->json(
                    [
                        'status'        => true,
                        'status_code'   => '00',
                        'message'       => "Data berhasil ditambahkan",
                    ],
                    200
                );
            } else {
                broadcast(new MessageSent($message));


                DB::table('message_ticket')->insert([
                    "message"               => $message,
                    'id_customer'           => $request->id_customer,
                    'id_ticket'             => $request->id_ticket,
                    'id_level_1_agent'      => $request->id_level_1_agent,
                    'id_level_2_agent'      => $request->id_level_2_agent,
                    'id_level_3_agent'      => $request->id_level_3_agent,
                    'id_level_4_agent'      => $request->id_level_4_agent,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);


                return response()->json(
                    [
                        'status'        => true,
                        'status_code'   => '00',
                        'message'       => "Data berhasil ditambahkan",
                    ],
                    200
                );
            }
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
