<?php

use App\Http\Controllers\Api\Dashboard\Customer\JumlahStatusTicketController;
use App\Http\Controllers\Api\Email\VerifikasiEmailController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Faq\FaqCategoryController;
use App\Http\Controllers\Api\MasterData\Kategori\KategoriTiket;
use App\Http\Controllers\Api\Faq\FaqQuestionController;
use App\Http\Controllers\Api\MasterData\Level\LevelController;
use App\Http\Controllers\Api\MasterData\Severity\SeverityController;
use App\Http\Controllers\Api\MasterData\Status\StatusController;
use App\Http\Controllers\Api\Message\MessageController;
use App\Http\Controllers\Api\Ticket\TicketController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/send-message', [MessageController::class, 'sendMessage']);



Route::post('auth/verifikasi_email', [VerifikasiEmailController::class, 'verifikasiEmail']);
Route::post('auth/verifikasi_code_email', [VerifikasiEmailController::class, 'verifikasiCodeEmail']);


Route::post('v1/auth/customer/login', [AuthController::class, 'login'])->name('login');
Route::post('v1/auth/customer/register', [AuthController::class, 'register']);




// CUSTOMER API
Route::group(['middleware' => ['api', 'verify.token']], function () {
    Route::group(['prefix' => 'v1'], function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/refresh', [AuthController::class, 'refresh']);
        Route::post('auth/customer/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('auth/customer/me', [AuthController::class, 'me'])->name('me');
        Route::post('auth/customer/refresh', [AuthController::class, 'refresh']);


        Route::group(['prefix' => 'customer'], function () {
            // FAQ
            // questionphp a
            Route::group(['prefix' => 'faq'], function () {
                Route::get('/get_question', [FaqQuestionController::class, 'GetFaqQuestion'])->name('faq.get_question');
            });

            //kategori tiket
            Route::group(['prefix' => 'kategori_tiket'], function () {
                Route::get('/get', [KategoriTiket::class, 'getDataKategoriTiket'])->name('kategori_tiket.get');
            });

            //Severity
            Route::group(['prefix' => 'severity'], function () {
                Route::get('/get', [SeverityController::class, 'getDataSeverity'])->name('severity.get');
            });

            Route::group(['prefix' => 'status'], function () {
                Route::get('/get_status_ticket', [StatusController::class, 'getDataStatus'])->name('status.get_status_ticket');
            });

            // Tiket
            Route::group(['prefix' => 'ticket'], function () {
                Route::post('get_ticket', [TicketController::class, 'getTicket'])->name('getTicket.get_ticket');
                Route::get('get_ticket_id/{id}', [TicketController::class, 'show'])->name('ticket.get_ticket_id');
                Route::post('create_ticket_customer', [TicketController::class, 'store'])->name('ticket.create_ticket_customer');


                // chat ticket - customer
                Route::group(['prefix' => 'message_customer'], function () {
                    Route::post('get_message', [MessageController::class, 'getMessageById'])->name('message_customer.get_message');
                    Route::post('create_message', [MessageController::class, 'CreateMessage'])->name('message_customer.create_message');
                });
            });

            // Dashboard
            Route::group(['prefix' => 'dashboard'], function () {
                Route::post('get_report_tiket', [JumlahStatusTicketController::class, 'jumlahStatusTicket'])->name('customer.get_report_tiket');
            });
        });
    });
});

//  END  CUSTOMER API


// Agent / TECHNIKAL API
Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'faq'], function () {
        // category
        Route::get('/get_category', [FaqCategoryController::class, 'GetFaqCategory'])->name('faq.get_category');
        Route::get('/get_category_name', [FaqCategoryController::class, 'GetFaqCategoryName'])->name('faq.get_category_name');
        Route::post('/create_category', [FaqCategoryController::class, 'CreateFaqCategory'])->name('faq.create_category');
        Route::post('/update_category/{id}', [FaqCategoryController::class, 'UpdateFaqCategory'])->name('faq.update_category');
        Route::get('/get_category_id/{id}', [FaqCategoryController::class, 'GetFaqCategoryById'])->name('faq.get_category_id');
        Route::delete('/delete_category/{id}', [FaqCategoryController::class, 'DeleteFaqCategory'])->name('faq.delete_category');
        // questionph
        Route::get('/get_questions', [FaqQuestionController::class, 'GetFaqQuestion'])->name('faq.get_questions');
        Route::post('/create_question', [FaqQuestionController::class, 'CreateFaqQuestion'])->name('faq.create_question');
        Route::post('/update_question/{id}', [FaqQuestionController::class, 'UpdateFaqQuestion'])->name('faq.update_question');
        Route::get('/get_question_id/{id}', [FaqQuestionController::class, 'GetFaqQuestionById'])->name('faq.get_question_id');
        Route::delete('/delete_question/{id}', [FaqQuestionController::class, 'DeleteFaqQuestion'])->name('faq.delete_question');
    });


    //kategori tiket
    Route::group(['prefix' => 'kategori_tiket'], function () {
        Route::get('/get_ticket', [KategoriTiket::class, 'getDataKategoriTiket'])->name('kategori_tiket.get_ticket');
        Route::post('/add', [KategoriTiket::class, 'addDataKategoriTiket'])->name('kategori_tiket.addDataKategoriTiket');
        Route::put('/update/{id}', [KategoriTiket::class, 'updateDataKategoriTiket'])->name('kategori_tiket.updateDataKategoriTiket');
        Route::get('/get_ticket/{id}', [KategoriTiket::class, 'getDataKategoriTiketById'])->name('kategori_tiket.getDataKategoriTiketById');
        Route::delete('/delete/{id}', [KategoriTiket::class, 'deleteDataKategoriTiket'])->name('kategori_tiket.deleteDataKategoriTiket');
    });


    //Severity
    Route::group(['prefix' => 'severity'], function () {
        Route::post('/add', [SeverityController::class, 'addDataSeverity'])->name('severity.add');
        Route::put('/update/{id}', [SeverityController::class, 'updateDataSeverity'])->name('severity.update');
        Route::get('/get/{id}', [SeverityController::class, 'getDataSeverityById'])->name('severity.getById');
        Route::delete('/delete/{id}', [SeverityController::class, 'deleteDataSeverity'])->name('severity.delete');
    });


    //Level
    Route::group(['prefix' => 'level'], function () {
        Route::get('/get', [LevelController::class, 'getDataLevel'])->name('level.get');
        Route::post('/add', [LevelController::class, 'addDataLevel'])->name('level.add');
        Route::put('/update/{id}', [LevelController::class, 'updateDataLevel'])->name('level.update');
        Route::get('/get/{id}', [LevelController::class, 'getDataLevelById'])->name('level.getById');
        Route::delete('/delete/{id}', [LevelController::class, 'deleteDataLevel'])->name('level.delete');
    });

    // status -> ticket
    Route::group(['prefix' => 'status'], function () {
        Route::post('/create_status_ticket', [StatusController::class, 'addDataStatus'])->name('status.create_status_ticket');
        Route::post('/update_status_ticket/{id}', [StatusController::class, 'updateDataSatus'])->name('status.update_status_ticket');
        Route::get('/get_status_ticket_id/{id}', [StatusController::class, 'getDataSetatusById'])->name('status.get_status_ticket_id');
        Route::delete('/delete_status_ticket/{id}', [StatusController::class, 'deleteDataStatus'])->name('status.delete_status_ticket');
    });

    // ticket
    Route::group(['prefix' => 'ticket'], function () {
        Route::get('get_ticket_agent', [TicketController::class, 'getTicketAgents'])->name('ticket.get_ticket_agent');
        Route::get('get_ticket/{id}', [TicketController::class, 'show'])->name('ticket.get_ticket');
        Route::post('create_ticket', [TicketController::class, 'store'])->name('ticket.create_ticket');
        Route::post('update_ticket/{id}', [TicketController::class, 'update'])->name('ticket.update_ticket');
        Route::get('/softDelete/{id}', [TicketController::class, 'softDelete'])->name('ticket.softDelete');
        Route::delete('delete/{id}', [TicketController::class, 'destroy'])->name('ticket.delete');


        // chat ticket - customer
        Route::group(['prefix' => 'message_agent'], function () {
            // Route::post('get_message', [MessageController::class, 'getMessageById'])->name('message_customer.get_message');
            // Route::post('create_message', [MessageController::class, 'CreateMessage'])->name('message_customer.create_message');
        });
    });
});
