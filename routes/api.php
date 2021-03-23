<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Library\LoanDistribution\GenerateLenderDataArray;
use App\Library\LoanDistribution\TestDistributor;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web']], static function () {
    // Auth Routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Checking Unique Email
    Route::post('/unique-email', [UserController::class, 'uniqueEmail']);

    // Sending an Email
    Route::post('/send-verify-email', [UserController::class, 'sendVerifyEmail']);

    // Verify Email
    Route::get('/verify-email/{email}/{token}', [UserController::class, 'verifyEmail']);

    // This is temporary
    Route::get('/testing-distributing/{amount}', static function ($amount) {
        $loan_distributor = new TestDistributor($amount, uniqid('', true));
        return $loan_distributor->distribute();
    });

    // This is temporary too
    Route::get('/lender-data/{amount}', static function (int $amount) {
        $temp = new GenerateLenderDataArray();
        return $temp->generate($amount);
    });
});
