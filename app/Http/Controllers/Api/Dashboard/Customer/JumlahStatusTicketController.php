<?php

namespace App\Http\Controllers\Api\Dashboard\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JumlahStatusTicketController extends Controller
{
    public function jumlahStatusTicket(Request $request)
    {
        try {

            $totalTicket          = DB::table('ticket')->where('id_customer', $request->idCustomer)->orderBy('created_at', 'desc')->count();
            $totalTicketNew       = DB::table('ticket')->where('id_customer', $request->idCustomer)
                ->where('status_open_tiket', 'ILIKE', "%new%")
                ->count();
            $totalInprogress      = DB::table('ticket')->where('id_customer', $request->idCustomer)
                ->where('status_open_tiket', 'ILIKE', "%Inprogress%")
                ->count();
            $totalOnHold          = DB::table('ticket')->where('id_customer', $request->idCustomer)
                ->where('status_open_tiket', 'ILIKE', "%On-Hold%")
                ->count();
            $totalClosed         = DB::table('ticket')->where('id_customer', $request->idCustomer)
                ->where('status_open_tiket', 'ILIKE', "%Closed%")
                ->count();

            $response = [
                'status' => true,
                'response' => [
                    [
                        'title'         => 'Total Tickets',
                        'icon'          => 'f7:tickets',
                        'subtitle'      => 'Total Tickets',
                        'stats'         => $totalTicket,
                    ], [
                        'title'         => 'New Tickets',
                        'avatarColor'   => 'info',
                        'icon'          => 'f7:tickets',
                        'subtitle'      => 'New Tickets',
                        'stats'         => $totalTicketNew,
                    ],
                    [
                        'title'         => 'Inprogress Tickets',
                        'avatarColor'   => 'warning',
                        'icon'          => 'f7:tickets',
                        'subtitle'      => 'Inprogress Tickets',
                        'stats'         => $totalInprogress,
                    ],  [
                        'title'         => 'On-Hold Tickets',
                        'avatarColor'   => 'error',
                        'icon'          => 'f7:tickets',
                        'subtitle'      => 'On-Hold Tickets',
                        'stats'         => $totalOnHold,

                    ],
                    [
                        'title'         => 'Closed Tickets',
                        'avatarColor'   => 'success',
                        'icon'          => 'f7:tickets',
                        'subtitle'      => 'Closed Tickets',
                        'stats'         => $totalClosed,

                    ]
                ]
            ];

            return response()->json($response)->getData(true);
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
