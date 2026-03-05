<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ConfigurationController;
use App\Http\Controllers\API\CommunityController;
use App\Http\Controllers\API\AttendenceController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\AchievementController;
use App\Http\Controllers\API\RecipeController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ActivityController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\BMICalculatorController;
use App\Http\Controllers\API\TestimonialController;
use App\Http\Controllers\API\MealTypeController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\BodyCompositionController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\CronjobController;


Route::post('configurations', [ConfigurationController::class, 'index']);

// Login
Route::post('login', [AuthController::class, 'login']);
//-----------------

// Users Routes
Route::prefix('users')->group(function () {

    // User Profile
    Route::post('view-profile', [UserController::class, 'viewProfile']);
    Route::post('update-profile', [UserController::class, 'updateProfile']);
    //---------------

    // User Change Password
    Route::post('change-password', [UserController::class, 'changePassword']);
    //---------------------

    // User Device Info 
    Route::post('update-device-info', [UserController::class, 'updateDeviceInfo']);
    //-----------------

    // // Update Notification Status
    // Route::post('update-notification-status', [UserController::class, 'updateNotification']);
    // //--------------------

    // // User Notifications
    Route::post('notifications', [UserController::class, 'getNotifications']);
    Route::any('test-notification', [UserController::class, 'testNotification']);
    // //-------------------

    // Delete/Deactivate my account
    Route::post('delete-account', [UserController::class, 'deleteAccount']);
    //--------------------

    // Logout API
    Route::post('logout', [UserController::class, 'logout']);
    //--------------------

});
//-------------

Route::prefix('achievements')->group(function () {
    Route::post('/', [AchievementController::class, 'index']);
});

// Community Routes
Route::prefix('communities')->group(function () {
    Route::post('add', [CommunityController::class, 'add']);
});
//---------------

Route::prefix('activities')->group(function () {
    Route::post('/', [ActivityController::class, 'index']);
});

Route::prefix('videos')->group(function () {
    Route::post('/', [VideoController::class, 'index']);
});

Route::prefix('testimonials')->group(function () {
    Route::post('/', [TestimonialController::class, 'index']);
});

Route::prefix('meal-type')->group(function () {
    Route::post('/', [MealTypeController::class, 'index']);
});

Route::prefix('attendence')->group(function () {
    Route::post('/', [AttendenceController::class, 'view']);
    Route::post('add', [AttendenceController::class, 'add']);
    Route::post('check', [AttendenceController::class, 'checkAttendence']);
    Route::post('update-weight', [AttendenceController::class, 'updateWeight']);
    Route::post('update-goal', [AttendenceController::class, 'updateGoal']);
    Route::post('update-weight-image', [AttendenceController::class, 'updateWeightImage']);
});

Route::prefix('recipes')->group(function () {
    Route::post('/', [RecipeController::class, 'index']);
    Route::post('/details', [RecipeController::class, 'details']);
});

// Product Routes
Route::prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'index']);
    Route::post('/details', [ProductController::class, 'details']);
});
//--------------

Route::prefix('dashboard')->group(function () {
    Route::post('/', [DashboardController::class, 'index']);
});

Route::prefix('dashboard')->group(function () {
    Route::post('/weight', [DashboardController::class, 'weight']);
});


// Orders Routes
Route::prefix('orders')->group(function () {
    Route::post('place-order', [OrderController::class, 'placeOrder']);
    Route::any('/', [OrderController::class, 'index']);
    Route::post('details', [OrderController::class, 'details']);
});
//--------------

Route::prefix('bmi-calculator')->group(function () {
    Route::post('/', [BMICalculatorController::class, 'index']);
});

Route::prefix('body-composition')->group(function () {
    Route::post('/', [BodyCompositionController::class, 'index']);
});

Route::prefix('payment-history')->group(function () {
    Route::post('/', [PaymentController::class, 'index']);
});

Route::prefix('rating')->group(function () {
    Route::post('add', [RatingController::class, 'add']);
});

Route::prefix('cronjob')->group(function () {
    Route::any('10days', [CronjobController::class, 'days10']);
    Route::any('5days', [CronjobController::class, 'days5']);
    Route::any('1days', [CronjobController::class, 'days1']);
    Route::any('meal-type', [CronjobController::class, 'mealType']);
    Route::any('water-notifications', [CronjobController::class, 'waterNotifications']);
    Route::any('pending-notifications', [CronjobController::class, 'pendingNotifications']);
    Route::any('pending-amount', [CronjobController::class, 'pendingAmount']);
});