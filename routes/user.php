<?php

use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\SslCommerzPaymentController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationController;
use App\Http\Resources\UserResource;
use App\Http\Resources\VerificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Authenticated Routes
Route::group(['middleware' => ['auth:sanctum']], static function () {

    // get current user
    Route::get('/', static function (Request $request) {
        return response()->json([
            'user' => new UserResource($request->user()),
            'verification' => $request->user()->verification()->exists()
                ? new VerificationResource($request->user()->verification)
                : null
        ]);
    });

    // Get Current User Balance
    Route::get('/balance', static function (Request $request) {
        return response()->json([
            'balance' => $request->user()->balance,
        ]);
    });

    // is contact-verified
    Route::get('/contact-verified', [UserController::class, 'isContactVerified']);

    // Checking Unique Email Excluding Id
    Route::post('/unique-email-excluding-id', [UserController::class, 'uniqueEmailExcludingId']);

    // getting the user with verification
    Route::get('/user-with-verification', [UserController::class, 'userWithVerificationData']);

    // Verify Email OTP
    Route::post('/verify-email-otp', [UserController::class, 'verifyEmailOtp']);

    // Sending OTP to mobile no
    Route::post('/send-mobile-otp', [UserController::class, 'sendMobileOTP']);

    // Verify Mobile OTP
    Route::post('/verify-mobile-no', [UserController::class, 'verifyMobileNo']);

    // verify an user
    Route::post('/verify', [VerificationController::class, 'verifyUser']);

    // borrower asking for a new loan
    Route::post('/new-loan', [LoanController::class, 'newLoan']);

    // get all Loans
    Route::get('/loans/{loanType}', [LoanController::class, 'getLoans']);

    // Deposit Routes
    Route::get('/deposit', [SslCommerzPaymentController::class, 'exampleEasyCheckout']);

    // Get All Deposit Transactions
    Route::get('/get-all-deposits', [TransactionController::class, 'getAllDeposits']);

    // Get All Withdrawal Transactions
    Route::get('/get-all-withdrawals', [TransactionController::class, 'getAllWithdrawals']);

    // Withdraw
    Route::post('/withdraw', [TransactionController::class, 'withdraw']);

    // Fetching Dashboard Data
    Route::get('/recent-data', [UserController::class, 'recentData']);

    // Fetching Alternate Dashboard Data
    Route::get('/dashboard-data', [UserController::class, 'dashboardData']);

    // Get Dashboard Notifications
    Route::get('/dashboard-notifications', [UserController::class, 'getNotifications']);

    // Get All Notifications
    Route::get('/get-all-notifications', [UserController::class, 'getAllNotifications']);

    // Delete a Notification
    Route::post('/delete-single-notification/{id}', [UserController::class, 'deleteNotification']);

    // Personal User Settings , info should be address, email or mobile no
    Route::post('/personal/{info}', [UserController::class, 'updatePersonalSettings']);

    // Account User Settings , info should be language or close-account
    Route::post('/account/{info}', [UserController::class, 'updateAccountSettings']);

//    // Get Loan Preference
//    Route::get('/get-loan-preferences', [UserController::class, 'getLoanPreferences']);
//
    // Saving Loan Preference
    Route::post('/save-loan-preferences', [UserController::class, 'saveLoanPreferences']);

    // get all installments
    Route::get('/get-all-installments/{type}', [InstallmentController::class, 'getAllInstallments']);

    // get single Installment
    Route::get('/get-single-installment/{id}', [InstallmentController::class, 'getSingleInstallment']);

    // Pay Installment
    Route::post('/pay-installment', [InstallmentController::class, 'payInstallment']);
});
