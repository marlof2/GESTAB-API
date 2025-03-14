<?php

use App\Http\Controllers\AbilityController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\Auth\GoogleLoginController;
use Illuminate\Support\Facades\Route;

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


Route::get('', function () {
    return response()->json(['status' => 'API_ONLINE']);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [UserController::class, 'store']);
Route::post('/forgot-password', [ForgotPasswordController::class, 'forgotPassword']);
Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.reset');
Route::post('/webhookMercadoPago', [App\Http\Controllers\PaymentController::class, 'webhookMercadoPago']);

Route::prefix('google')->group(function () {
    Route::get('/auth', [GoogleLoginController::class, 'redirect']);
    Route::get('/callback', [GoogleLoginController::class, 'callback']);

    Route::get('/calendar/auth', [GoogleAuthController::class, 'redirect']);
    Route::get('/calendar/callback', [GoogleAuthController::class, 'callback']);
});


Route::prefix('combo')->group(function () {
    Route::get('/establishimentsUser/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'comboEstablishimentsById']);
    Route::get('/professionalByEstablishment/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'comboProfessionalByEstablishment']);
    Route::get('/servicesByEstablishment/{id}', [App\Http\Controllers\ServicesController::class, 'comboServicesByEstablishment']);
    Route::get('/userByEstablishiment/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'comboUserByEstablishiment']);
    // Route::get('/establishimentByResponsible/{id}', [App\Http\Controllers\EstablishmentController::class, 'establishimentByResponsible']);
});


Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/verify-token', function () {
        return response()->json([
            'status' => true,
            'message' => 'Token válido'
        ]);
    });

    Route::get("/me", [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::prefix('abilities')->group(function () {
        Route::get('/', [AbilityController::class, 'index']);
    });

    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->middleware('abilities:user_list');
        Route::post('/', [UserController::class, 'store'])->middleware('abilities:user_insert');
        Route::get('/listUsersByEstablishiment', [UserController::class, 'listUsersByEstablishiment'])->middleware('abilities:user_by_id');
        Route::get('/{id}', [UserController::class, 'show'])->middleware('abilities:user_by_id');
        // Route::put('/{id}', [UserController::class, 'update'])->middleware('abilities:user_edit');
        Route::patch('/{id}', [UserController::class, 'update'])->middleware('abilities:user_edit');
        Route::delete('/{id}', [UserController::class, 'destroy'])->middleware('abilities:user_delete');
        Route::post('/alterarsenha', [UserController::class, 'alterarSenhaUsuario'])->middleware('abilities:user_change_password');
        // Route::post('/resetSenha', [UserController::class, 'resetSenha'])->middleware('abilities:user_reset_senha');
        Route::get('/establishments/{id}', [UserController::class, 'establishments'])->middleware('abilities:user_by_id');
    });

    Route::prefix('profiles')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->middleware('abilities:profile_list');
        Route::post('/', [ProfileController::class, 'store'])->middleware('abilities:profile_insert');
        Route::get('/{id}', [ProfileController::class, 'show'])->middleware('abilities:profile_by_id');
        Route::put('/{id}', [ProfileController::class, 'update'])->middleware('abilities:profile_edit');
        Route::delete('/{id}', [ProfileController::class, 'destroy'])->middleware('abilities:profile_delete');

        Route::get('/abilities/{id}', [ProfileController::class, 'getAbilities'])->middleware('abilities:profile_list_ability');
        Route::post('/addAbilities', [ProfileController::class, 'addPermissions']);
    });


    Route::prefix('type_of_person')->group(function () {
        Route::get('/', [App\Http\Controllers\TypeOfPersonController::class, 'index']);
    });


    Route::prefix('establishments')->group(function () {
        Route::get('/', [App\Http\Controllers\EstablishmentController::class, 'index'])->middleware('abilities:establishment_list');
        Route::get('/listEstablishimentByUser', [App\Http\Controllers\EstablishmentController::class, 'listEstablishimentByUser'])->middleware('abilities:establishment_list');
        Route::post('/', [App\Http\Controllers\EstablishmentController::class, 'store'])->middleware('abilities:establishment_insert');
        Route::get('/{id}', [App\Http\Controllers\EstablishmentController::class, 'show'])->middleware('abilities:establishment_by_id');
        Route::put('/{id}', [App\Http\Controllers\EstablishmentController::class, 'update'])->middleware('abilities:establishment_edit');
        Route::delete('/{id}', [App\Http\Controllers\EstablishmentController::class, 'destroy'])->middleware('abilities:establishment_delete');
        Route::patch('/{id}', [App\Http\Controllers\EstablishmentController::class, 'restore'])->middleware('abilities:establishment_delete');
        Route::get('/checkPaymentActive/{establishment_id}/{user_id}', [App\Http\Controllers\EstablishmentController::class, 'checkPaymentActive'])->middleware('abilities:establishment_by_id');
    });

    Route::prefix('services')->group(function () {
        Route::post('/', [App\Http\Controllers\ServicesController::class, 'store'])->middleware('abilities:services_insert');
        Route::get('/by_establishment', [App\Http\Controllers\ServicesController::class, 'index'])->middleware('abilities:services_list');
        Route::get('/{id}', [App\Http\Controllers\ServicesController::class, 'show'])->middleware('abilities:services_by_id');
        Route::put('/{id}', [App\Http\Controllers\ServicesController::class, 'update'])->middleware('abilities:services_edit');
        Route::delete('/{id}', [App\Http\Controllers\ServicesController::class, 'destroy'])->middleware('abilities:services_delete');
    });

    Route::prefix('establishment_services')->group(function () {
        Route::get('/', [App\Http\Controllers\EstablishmentServicesController::class, 'index'])->middleware('abilities:establishmentservices_list');
        Route::post('/', [App\Http\Controllers\EstablishmentServicesController::class, 'store'])->middleware('abilities:establishmentservices_insert');
        Route::get('/{id}', [App\Http\Controllers\EstablishmentServicesController::class, 'show'])->middleware('abilities:establishmentservices_by_id');
        // Route::put('/{id}', [App\Http\Controllers\EstablishmentServicesController::class, 'update'])->middleware('abilities:establishmentservices_edit');
        Route::delete('/', [App\Http\Controllers\EstablishmentServicesController::class, 'destroy'])->middleware('abilities:establishmentservices_delete');
    });

    Route::prefix('status')->group(function () {
        Route::get('/', [App\Http\Controllers\StatusController::class, 'index']);
    });


    Route::prefix('establishment_user')->group(function () {
        Route::get('user_by_establishment/{establishment_id}', [App\Http\Controllers\EstablishmentUserController::class, 'index'])->middleware('abilities:establishmentuser_list');
        Route::get('establishment_by_user/{user_id}', [App\Http\Controllers\EstablishmentUserController::class, 'establishimentByUser'])->middleware('abilities:establishmentuser_list');
        Route::put('/change-plan/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'updateHavePlanEstablishment'])->middleware('abilities:establishmentuser_edit');
        Route::post('/associationProfessionalAndEstablishment', [App\Http\Controllers\EstablishmentUserController::class, 'associationProfessionalAndEstablishment'])->middleware('abilities:establishmentuser_insert');
        Route::post('/associationClientAndEstablishment', [App\Http\Controllers\EstablishmentUserController::class, 'associationClientAndEstablishment'])->middleware('abilities:establishmentuser_insert');
        Route::get('/{user_id}', [App\Http\Controllers\EstablishmentUserController::class, 'show'])->middleware('abilities:establishmentuser_by_id');
        Route::put('/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'update'])->middleware('abilities:establishmentuser_edit');
        Route::delete('/{id}', [App\Http\Controllers\EstablishmentUserController::class, 'destroy'])->middleware('abilities:establishmentuser_delete');
    });

    Route::prefix('list')->group(function () {
        Route::get('/', [App\Http\Controllers\ListController::class, 'index'])->middleware('abilities:list_list');
        Route::get('/hystoric-user', [App\Http\Controllers\ListController::class, 'hystoricUser'])->middleware('abilities:list_list');
        Route::get('exportReport/', [App\Http\Controllers\ListController::class, 'exportReport'])->middleware('abilities:list_list');
        Route::get('exportReportDownload/', [App\Http\Controllers\ListController::class, 'exportReportDownload'])->middleware('abilities:list_list');
        Route::post('/', [App\Http\Controllers\ListController::class, 'store'])->middleware('abilities:list_insert');
        Route::get('/{id}', [App\Http\Controllers\ListController::class, 'show'])->middleware('abilities:list_by_id');
        Route::put('/{id}', [App\Http\Controllers\ListController::class, 'update'])->middleware('abilities:list_edit');
        Route::delete('/{id}', [App\Http\Controllers\ListController::class, 'destroy'])->middleware('abilities:list_delete');

        Route::put('statusEmAtendimento/{id}', [App\Http\Controllers\ListController::class, 'statusEmAtendimento'])->middleware('abilities:list_edit');
        Route::put('statusConcluido/{id}', [App\Http\Controllers\ListController::class, 'statusConcluido'])->middleware('abilities:list_edit');
        Route::put('statusAguardandoAtendimento/{id}', [App\Http\Controllers\ListController::class, 'statusAguardandoAtendimento'])->middleware('abilities:list_edit');
        Route::put('statusDesistiu/{id}', [App\Http\Controllers\ListController::class, 'statusDesistiu'])->middleware('abilities:list_edit');
    });

    Route::prefix('feedbacks')->group(function () {
        Route::get('/', [App\Http\Controllers\FeedbacksController::class, 'index'])->middleware('abilities:feedbacks_list');
        // Route::post('/', [App\Http\Controllers\FeedbacksController::class, 'store'])->middleware('abilities:feedbacks_insert');
        Route::get('/{user_id}', [App\Http\Controllers\FeedbacksController::class, 'show'])->middleware('abilities:feedbacks_by_id');
        // Route::put('/{id}', [App\Http\Controllers\FeedbacksController::class, 'update'])->middleware('abilities:feedbacks_edit');
        // Route::delete('/{id}', [App\Http\Controllers\FeedbacksController::class, 'destroy'])->middleware('abilities:feedbacks_delete');
    });

    Route::prefix('categories')->group(function () {
        Route::get('/', [App\Http\Controllers\CategoriesController::class, 'index'])->middleware('abilities:feedbacks_list');
    });

    Route::prefix('payments')->group(function () {
        Route::get('status/{id}', [App\Http\Controllers\PaymentController::class, 'show']);
        Route::post('/createPreference', [App\Http\Controllers\PaymentController::class, 'createPreference']);
        Route::get('/hasActivePayment/{establishment_id}', [App\Http\Controllers\PaymentController::class, 'hasActivePayment']);
    });

    Route::prefix('blockcalendars')->group(function () {
        Route::get('/', [App\Http\Controllers\BlockCalendarController::class, 'index']);
        Route::get('/getBlockCalendarByEstablishmentAndUser', [App\Http\Controllers\BlockCalendarController::class, 'getBlockCalendarByEstablishmentAndUser']);
        Route::post('/', [App\Http\Controllers\BlockCalendarController::class, 'store']);
        Route::delete('/{id}', [App\Http\Controllers\BlockCalendarController::class, 'destroy']);
    });

    Route::prefix('google-calendar')->middleware(['google.token'])->group(function () {
        // Route::get('/', [GoogleCalendarController::class, 'getEventsByUser']);
        Route::post('/', [GoogleCalendarController::class, 'store']);
        // Route::put('/{eventId}', [GoogleCalendarController::class, 'updateEvent']);
        Route::delete('/{user_id}/{list_id}', [GoogleCalendarController::class, 'destroy']);
    });
});
