<?php

namespace App\Http\Controllers\Api\Ticket;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



class TicketController extends Controller
{
    // Get all tickets
    public function getTicket(Request $request)
    {
        $per_page = $request->get('per_page', 20);
        $idCustomer = $request->idCustomer;


        try {

            if ($request->status) {
                $tickets = DB::table('ticket')->where('id_customer', $idCustomer)
                    ->where('status_open_tiket', 'ILIKE', "%" . $request->status . "%")->orderBy('created_at', 'desc')->paginate($per_page);

                if (!$tickets) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Upppps, data tidak di temukan. 😝',
                        'response' => [],
                    ], 500);
                } else {

                    return response()->json(
                        [
                            'status'     => true,
                            'response'   => $tickets
                        ],
                        200
                    );
                }
            }

            if ($request->nomer_ticket) {
                $payloadNomerTicket = $request->nomer_ticket;

                $tickets =  DB::table('ticket')->orderBy('created_at', 'desc')
                    ->where('id_customer', $idCustomer)
                    ->where('nomer_ticket',  $payloadNomerTicket)
                    ->paginate($per_page);

                if (!$tickets) {

                    return response()->json([
                        'status' => false,
                        'message' => 'Upppps, data tidak di temukan. 😝',
                        'response' => [],
                    ], 500);
                } else {

                    return response()->json([
                        'status'        => true,
                        'status_code'   => '00',
                        'message'       => 'sucesss',
                        'response'      => $tickets
                    ], 200);
                }
            }


            $tickets = DB::table('ticket')->orderBy('created_at', 'desc')->where('id_customer', $idCustomer)->paginate($per_page);
            return response()->json([
                'status'        => true,
                'status_code'   => '00',
                'message'       => 'sucesss',
                'response'        => $tickets
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching tickets: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch tickets.' . $e->getMessage()
            ], 500);
        }
    }

    public function getTicketAgents(Request $request)
    {
        $per_page = $request->get('per_page', 20);
        try {

            if ($request->status) {
                $tickets = DB::table('ticket')->where('status_open_tiket', 'ILIKE', "%" . $request->status . "%")->orderBy('created_at', 'desc')->paginate($per_page);

                if (!$tickets) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Upppps, data tidak di temukan. 😝',
                        'response' => [],
                    ], 500);
                } else {

                    return response()->json(
                        [
                            'status'     => true,
                            'response'   => $tickets
                        ],
                        200
                    );
                }
            }

            if ($request->nomer_ticket) {
                $payloadNomerTicket = $request->nomer_ticket;

                $tickets =  DB::table('ticket')->orderBy('created_at', 'desc')
                    ->where('nomer_ticket',  $payloadNomerTicket)
                    ->paginate($per_page);

                if (!$tickets) {

                    return response()->json([
                        'status' => false,
                        'message' => 'Upppps, data tidak di temukan. 😝',
                        'response' => [],
                    ], 500);
                } else {

                    return response()->json([
                        'status'        => true,
                        'status_code'   => '00',
                        'message'       => 'sucesss',
                        'response'      => $tickets
                    ], 200);
                }
            }


            $tickets = DB::table('ticket')->orderBy('created_at', 'desc')->paginate($per_page);
            return response()->json([
                'status'        => true,
                'status_code'   => '00',
                'message'       => 'sucesss',
                'response'        => $tickets
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching tickets: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to fetch tickets.' . $e->getMessage()
            ], 500);
        }
    }

    // Get a specific ticket by ID
    public function show($id)
    {
        try {
            $ticket = DB::table('ticket')->where('id', $id)->first();

            if (!$ticket) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Ticket not found'
                ], 500);
            }

            return response()->json([
                'status'   => true,
                'response' => $ticket
            ], 200);
        } catch (\Exception $e) {

            Log::error("Error fetching ticket: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch ticket.'
            ], 500);
        }
    }

    // Create a new ticket
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_customer'     => 'required|string',
                'judul'             => 'required|string',
                'deskripsi'         => 'required|string|max:1000',
                'id_customer'       => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $customer = DB::table('users')->where('id', $request->id_customer)->first();


            if (!$customer) {
                $error = 'ID Customer tidak ditemukan!';
                Log::error($error);

                return response()->json([
                    'status'  => false,
                    'message' => $error,
                ], 422);
            }

            // generrate nomer ticket
            $ticketNumber = $this->generateTicketNumber();



            if ($request->lampiran) {

                $path = '/uploads/lampiran';
                $lampiran = $request->file('lampiran');
                $extension = $lampiran->getClientOriginalExtension();
                $resultLampiran = $path . '/' . time() . '-' . Str::random(5) . '.' . $extension;

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0775, true, true);
                }

                $lampiran->move(public_path('/uploads/lampiran'), $resultLampiran);

                DB::table('ticket')->insert([
                    'nomer_ticket'          => $ticketNumber,
                    'nama_customer'         => $request->nama_customer,
                    'judul'                 => $request->judul,
                    'deskripsi'             => $request->deskripsi,
                    'project_name'          => $customer->project_name ?? '-',
                    'note'                  => $request->note,
                    'tipe_severity'         => $request->tipe_severity,
                    'kategory'              => $request->kategory,
                    'lampiran'              => $resultLampiran,
                    'level_1'               => true,
                    // 'level_2'               => $request->level_2 ?? 0,
                    // 'level_3'               => $request->level_3 ?? 0,
                    // 'level_4'               => $request->level_4 ?? 0,
                    'penugasan'             => $request->penugasan,
                    'end_date'              => $request->end_date,
                    'status_open_tiket'     => 'new',
                    'id_customer'           => $request->id_customer,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                ]);

                return response()->json([
                    'status'      => true,
                    'status_code' => '00',
                    'message'     => 'Ticket created successfully',
                ], 201);
            } else {

                DB::table('ticket')->insert([
                    'nomer_ticket'          => $ticketNumber,
                    'nama_customer'         => $request->nama_customer,
                    'judul'                 => $request->judul,
                    'deskripsi'             => $request->deskripsi,
                    'project_name'          => $customer->project_name ?? '-',
                    'note'                  => $request->note,
                    'tipe_severity'         => $request->tipe_severity,
                    'kategory'              => $request->kategory,
                    'level_1'               => true,
                    // 'level_2'               => $request->level_2 ?? 0,
                    // 'level_3'               => $request->level_3 ?? 0,
                    // 'level_4'               => $request->level_4 ?? 0,
                    'penugasan'             => $request->penugasan,
                    'end_date'              => $request->end_date,
                    'status_open_tiket'     => 'New',
                    'id_customer'           => $request->id_customer,
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                    'deleted'               => true,
                ]);


                return response()->json([
                    'status'      => true,
                    'status_code' => '00',
                    'message'     => 'Ticket created successfully',
                ], 201);
            }
        } catch (\Exception $e) {
            Log::error("Error creating ticket: " . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Failed to create ticket.'
            ], 500);
        }
    }

    // Update a specific ticket by ID
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nama_customer'     => 'required|string',
                'judul'             => 'required|string',
                'deskripsi'         => 'nullable|string',
                'project_name'      => 'nullable|string',
                'note'              => 'nullable|string',
                'tipe_severity'     => 'nullable|string',
                'kategory'          => 'nullable|string',
                'lampiran'          => 'nullable|string',
                'penugasan'         => 'nullable|string',
                'end_date'          => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Validation error',
                    'errors'  => $validator->errors()
                ], 422);
            }

            $ticket = DB::table('ticket')->where('id', $id)->first();

            if (!$ticket) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Ticket not found'
                ], 500);
            }

            DB::table('ticket')->where('id', $id)->update([
                'nomer_ticket'          => $request->nomer_ticket,
                'nama_customer'         => $request->nama_customer,
                'judul'                 => $request->judul,
                'deskripsi'             => $request->deskripsi,
                'project_name'          => $request->project_name,
                'note'                  => $request->note,
                'tipe_severity'         => $request->tipe_severity,
                'kategory'              => $request->kategory,
                'lampiran'              => $request->lampiran,
                'level_1'               => $request->level_1 ?? 0,
                'level_2'               => $request->level_2 ?? 0,
                'level_3'               => $request->level_3 ?? 0,
                'level_4'               => $request->level_4 ?? 0,
                'penugasan'             => $request->penugasan,
                'end_date'              => $request->end_date,
                'status_open_tiket_id'  => $request->status_open_tiket_id,
                'updated_at'            => Carbon::now(),
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Ticket updated successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error updating ticket: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to update ticket.'
            ], 500);
        }
    }

    // Delete a specific ticket by ID
    public function destroy($id)
    {
        try {
            $ticket = DB::table('ticket')->where('id', $id)->first();

            if (!$ticket) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Ticket not found'
                ], 500);
            }

            DB::table('ticket')->where('id', $id)->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Ticket deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error deleting ticket: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to delete ticket.'
            ], 500);
        }
    }

    private  function generateTicketNumber()
    {
        $prefix = 'B2348 - FSI - ';
        // Ambil tiket terakhir yang ada
        $lastTicket = DB::table('ticket')
            ->orderBy('id', 'desc') // Urutkan berdasarkan ID atau kolom yang relevan
            ->first();

        if ($lastTicket) {
            // Ambil nomor terakhir dan tambahkan 1
            $lastNumber = (int)substr($lastTicket->nomer_ticket, strlen($prefix));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            // Jika tidak ada tiket sebelumnya, mulai dari 001
            $nextNumber = '001';
        }


        // Formatkan nomor tiket baru
        $newTicketNumber = $prefix . $nextNumber;


        return $newTicketNumber;
    }

    public function softDelete($id)
    {
        try {
            $ticket = DB::table('ticket')->where('id', $id)->first();

            if (!$ticket) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Ticket not found'
                ], 500);
            }

            DB::table('ticket')->where('id', $id)->update([
                'deleted'    => false,
                'updated_at' => Carbon::now()
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Ticket marked as deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error marking ticket as deleted: " . $e->getMessage());

            return response()->json([
                'status'  => false,
                'message' => 'Failed to mark ticket as deleted.'
            ], 500);
        }
    }
}
