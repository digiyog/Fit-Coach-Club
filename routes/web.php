<?php
error_reporting(0);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (config('app.env') == 'production') {
    \URL::forceScheme('https');
}

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('storage:link');
    $exitCode = Artisan::call('permission:cache-reset');
    return "All cache is cleared";
});

Route::get('/call-migrate', function () {
    $exitCode = Artisan::call('migrate');
    return "All migrations run";
});

Route::get('/', function () {
    return "";
});

Route::get('privacy-policy', function () {
    return view('privacy_policy');
});

// Admin Panel
Route::prefix('admin-panel')->group(function () {
    require __DIR__ . '/auth.php';

    // Dashboard
    Route::get('/', [Controllers\AdminPanel\DashboardController::class, 'index'])->middleware(['checkuserauth'])->name('adminPanel.dashboard');
    Route::get('/dashboard', [Controllers\AdminPanel\DashboardController::class, 'index'])->middleware(['checkuserauth'])->name('adminPanel.dashboard');
    //---------

    // Admin Profile
    Route::group(['prefix' => 'profile', 'middleware' => ['checkuserauth']], function () {
        Route::get('/', [Controllers\AdminPanel\ProfileController::class, 'index'])->name('adminPanel.profile');
        Route::post('/update', [Controllers\AdminPanel\ProfileController::class, 'update'])->name('adminPanel.profile.update');
        Route::post('/update-password', [Controllers\AdminPanel\ProfileController::class, 'updatePassword'])->name('adminPanel.profile.updatePassword');
        Route::post('/check-mobile', [Controllers\AdminPanel\ProfileController::class, 'checkMobile'])->name('adminPanel.profile.checkMobile');
        Route::post('/check-email', [Controllers\AdminPanel\ProfileController::class, 'checkEmail'])->name('adminPanel.profile.checkEmail');
    });
    //---------

    // Company Profile
    Route::group(['prefix' => 'company-profile', 'middleware' => ['checkuserauth']], function () {
        Route::get('/', [Controllers\AdminPanel\CompanyProfileController::class, 'index'])->name('adminPanel.company-profile.index');
        Route::post('/update', [Controllers\AdminPanel\CompanyProfileController::class, 'update'])->name('adminPanel.company-profile.update');
    });
    //--------

    // Cms Pages
    Route::group(['prefix' => 'cms-pages', 'middleware' => ['checkuserauth']], function () {
        Route::get('/', [Controllers\AdminPanel\CmsController::class, 'index'])->name('adminPanel.cms-pages.index');
        Route::get('/get-cms', [Controllers\AdminPanel\CmsController::class, 'getCms'])->name('adminPanel.cms-pages.getCms');
        Route::get('/edit/{id}', [Controllers\AdminPanel\CmsController::class, 'edit'])->name('adminPanel.cms-pages.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\CmsController::class, 'update'])->name('adminPanel.cms-pages.update');
    });
    //------    

    // Franchises
    Route::group(['prefix' => 'franchises', 'middleware' => ['checkuserauth']], function () {
        Route::get('/', [Controllers\AdminPanel\FranchiseController::class, 'index'])->name('adminPanel.franchises.index');
        Route::get('/get-franchises', [Controllers\AdminPanel\FranchiseController::class, 'getFranchises'])->name('adminPanel.franchises.getFranchises');
        Route::get('/create', [Controllers\AdminPanel\FranchiseController::class, 'create'])->name('adminPanel.franchises.create');
        Route::post('/', [Controllers\AdminPanel\FranchiseController::class, 'store'])->name('adminPanel.franchises.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\FranchiseController::class, 'edit'])->name('adminPanel.franchises.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\FranchiseController::class, 'update'])->name('adminPanel.franchises.update');
        Route::post('change-status', [Controllers\AdminPanel\FranchiseController::class, 'changeStatus'])->name('adminPanel.franchises.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\FranchiseController::class, 'destroy'])->name('adminPanel.franchises.destroy');
        Route::get('/details/{id}', [Controllers\AdminPanel\FranchiseController::class, 'details'])->name('adminPanel.franchises.details');
        Route::post('/update-order', [Controllers\AdminPanel\FranchiseController::class, 'updateOrder'])->name('adminPanel.franchises.updateOrder');
        Route::post('/check-mobile', [Controllers\AdminPanel\FranchiseController::class, 'checkMobile'])->name('adminPanel.franchises.checkMobile');
        Route::post('/check-email', [Controllers\AdminPanel\FranchiseController::class, 'checkEmail'])->name('adminPanel.franchises.checkEmail');
    });
    //--------

    // Products
    Route::group(['prefix' => 'products', 'middleware' => ['checkuserauth']], function () {
        Route::get('/', [Controllers\AdminPanel\ProductController::class, 'index'])->name('adminPanel.products.index');
        Route::get('/get-products', [Controllers\AdminPanel\ProductController::class, 'getProducts'])->name('adminPanel.products.getProducts');
        Route::get('/create', [Controllers\AdminPanel\ProductController::class, 'create'])->name('adminPanel.products.create');
        Route::post('/', [Controllers\AdminPanel\ProductController::class, 'store'])->name('adminPanel.products.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\ProductController::class, 'edit'])->name('adminPanel.products.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\ProductController::class, 'update'])->name('adminPanel.products.update');
        Route::post('/change-status', [Controllers\AdminPanel\ProductController::class, 'changeStatus'])->name('adminPanel.products.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\ProductController::class, 'destroy'])->name('adminPanel.products.destroy');
        Route::post('/update-order', [Controllers\AdminPanel\ProductController::class, 'updateOrder'])->name('adminPanel.products.updateOrder');
        Route::get('/view-description/{id}', [Controllers\AdminPanel\ProductController::class, 'viewDescription'])->name('adminPanel.products.viewDescription');
        Route::any('/image/delete/{id}', [Controllers\AdminPanel\ProductController::class, 'deleteImage']);
    });
    //--------

    // Meal Types
    Route::group(['prefix' => 'meal-types', 'middleware' => ['checkuserauth']], function () {
        Route::get('/index', [Controllers\AdminPanel\MealTypeController::class, 'index'])->name('adminPanel.meal-types.index');
        Route::get('/get-meal-types', [Controllers\AdminPanel\MealTypeController::class, 'getMealTypes'])->name('adminPanel.meal-types.getMealTypes');
        Route::get('/create', [Controllers\AdminPanel\MealTypeController::class, 'create'])->name('adminPanel.meal-types.create');
        Route::post('/', [Controllers\AdminPanel\MealTypeController::class, 'store'])->name('adminPanel.meal-types.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\MealTypeController::class, 'edit'])->name('adminPanel.meal-types.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\MealTypeController::class, 'update'])->name('adminPanel.meal-types.update');
        Route::post('/change-status', [Controllers\AdminPanel\MealTypeController::class, 'changeStatus'])->name('adminPanel.meal-types.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\MealTypeController::class, 'destroy'])->name('adminPanel.meal-types.destroy');
        Route::post('/update-order', [Controllers\AdminPanel\MealTypeController::class, 'updateOrder'])->name('adminPanel.meal-types.updateOrder');
        Route::get('/view-description/{id}', [Controllers\AdminPanel\MealTypeController::class, 'viewDescription'])->name('adminPanel.meal-types.viewDescription');
    });
    //--------

    // Product Types
    Route::group(['prefix' => 'product-types', 'middleware' => ['checkuserauth']], function () {
        Route::get('/index', [Controllers\AdminPanel\ProductTypeController::class, 'index'])->name('adminPanel.product-types.index');
        Route::get('/get-product-types', [Controllers\AdminPanel\ProductTypeController::class, 'getProductTypes'])->name('adminPanel.product-types.getProductTypes');
        Route::get('/create', [Controllers\AdminPanel\ProductTypeController::class, 'create'])->name('adminPanel.product-types.create');
        Route::post('/', [Controllers\AdminPanel\ProductTypeController::class, 'store'])->name('adminPanel.product-types.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\ProductTypeController::class, 'edit'])->name('adminPanel.product-types.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\ProductTypeController::class, 'update'])->name('adminPanel.product-types.update');
        Route::post('/change-status', [Controllers\AdminPanel\ProductTypeController::class, 'changeStatus'])->name('adminPanel.product-types.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\ProductTypeController::class, 'destroy'])->name('adminPanel.product-types.destroy');
    });
    //--------

    // Membership Plans
    Route::group(['prefix' => 'membership-plans', 'middleware' => ['checkuserauth']], function () {
        Route::get('/index', [Controllers\AdminPanel\MembershipPlanController::class, 'index'])->name('adminPanel.membership-plans.index');
        Route::get('/get-membership-plans', [Controllers\AdminPanel\MembershipPlanController::class, 'getMembershipPlans'])->name('adminPanel.membership-plans.getMembershipPlans');
        Route::get('/create', [Controllers\AdminPanel\MembershipPlanController::class, 'create'])->name('adminPanel.membership-plans.create');
        Route::post('/', [Controllers\AdminPanel\MembershipPlanController::class, 'store'])->name('adminPanel.membership-plans.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\MembershipPlanController::class, 'edit'])->name('adminPanel.membership-plans.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\MembershipPlanController::class, 'update'])->name('adminPanel.membership-plans.update');
        Route::post('/change-status', [Controllers\AdminPanel\MembershipPlanController::class, 'changeStatus'])->name('adminPanel.membership-plans.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\MembershipPlanController::class, 'destroy'])->name('adminPanel.membership-plans.destroy');
        Route::post('/update-order', [Controllers\AdminPanel\MembershipPlanController::class, 'updateOrder'])->name('adminPanel.membership-plans.updateOrder');
        Route::get('/view-description/{id}', [Controllers\AdminPanel\MembershipPlanController::class, 'viewDescription'])->name('adminPanel.membership-plans.viewDescription');
    });
    //--------

    // Franchise Membership Plans
    Route::group(['prefix' => 'franchise-membership-plans', 'middleware' => ['checkuserauth']], function () {
        Route::get('/index/{id?}', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'index'])->name('adminPanel.franchise-membership-plans.index');
        Route::get('/get-franchise-membership-plans', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'getFranchiseMembershipPlans'])->name('adminPanel.franchise-membership-plans.getFranchiseMembershipPlans');
        Route::get('/create', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'create'])->name('adminPanel.franchise-membership-plans.create');
        Route::post('/', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'store'])->name('adminPanel.franchise-membership-plans.store');
        Route::get('/edit/{id}', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'edit'])->name('adminPanel.franchise-membership-plans.edit');
        Route::post('/update/{id}', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'update'])->name('adminPanel.franchise-membership-plans.update');
        Route::post('/change-status', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'changeStatus'])->name('adminPanel.franchise-membership-plans.changeStatus');
        Route::delete('/destroy', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'destroy'])->name('adminPanel.franchise-membership-plans.destroy');
        Route::post('/update-order', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'updateOrder'])->name('adminPanel.franchise-membership-plans.updateOrder');
        Route::get('/view-description/{id}', [Controllers\AdminPanel\FranchiseMembershipPlanController::class, 'viewDescription'])->name('adminPanel.franchise-membership-plans.viewDescription');
    });
    //--------
});


// Nutrition Panel
Route::prefix('nutrition-panel')->group(function () {

    Route::get('/', [Controllers\NutritionPanel\LoginController::class, 'login'])->middleware(['checknouserauth'])->name('nutritionPanel.login');
    Route::get('login', [Controllers\NutritionPanel\LoginController::class, 'login'])->middleware(['checknouserauth'])->name('nutritionPanel.login');
    Route::post('login', [Controllers\NutritionPanel\LoginController::class, 'loginUser'])->middleware(['checknouserauth'])->name('nutritionPanel.login');

    Route::get('/logout', [Controllers\NutritionPanel\LoginController::class, 'destroy'])->middleware('auth')->name('nutritionPanel.logout');

    // Dashboard
    Route::any('/dashboard', [Controllers\NutritionPanel\DashboardController::class, 'dashboard'])->middleware(['checkfranchiseauth'])->name('nutritionPanel.dashboard');
    //---------

    // Nutrition Profile
    Route::group(['prefix' => 'profile', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\ProfileController::class, 'index'])->name('nutritionPanel.profile');
        Route::post('/update', [Controllers\NutritionPanel\ProfileController::class, 'update'])->name('nutritionPanel.profile.update');
        Route::post('/update-password', [Controllers\NutritionPanel\ProfileController::class, 'updatePassword'])->name('nutritionPanel.profile.updatePassword');
        Route::post('/check-mobile', [Controllers\NutritionPanel\ProfileController::class, 'checkMobile'])->name('nutritionPanel.profile.checkMobile');
        Route::post('/check-email', [Controllers\NutritionPanel\ProfileController::class, 'checkEmail'])->name('nutritionPanel.profile.checkEmail');
    });
    //---------

    // Nutrition Profile
    Route::group(['prefix' => 'change-password', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\ProfileController::class, 'index'])->name('nutritionPanel.change-password.index');
    });
    //---------

    // Users
    Route::group(['prefix' => 'users', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/get-users', [Controllers\NutritionPanel\UserController::class, 'getUsers'])->name('nutritionPanel.users.getUsers');
        Route::get('/create', [Controllers\NutritionPanel\UserController::class, 'create'])->name('nutritionPanel.users.create');
        Route::post('/', [Controllers\NutritionPanel\UserController::class, 'store'])->name('nutritionPanel.users.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\UserController::class, 'edit'])->name('nutritionPanel.users.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\UserController::class, 'update'])->name('nutritionPanel.users.update');
        Route::post('/change-status', [Controllers\NutritionPanel\UserController::class, 'changeStatus'])->name('nutritionPanel.users.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\UserController::class, 'destroy'])->name('nutritionPanel.users.destroy');
        Route::post('/check-mobile', [Controllers\NutritionPanel\UserController::class, 'checkMobile'])->name('nutritionPanel.users.checkMobile');
        Route::post('/check-email', [Controllers\NutritionPanel\UserController::class, 'checkEmail'])->name('nutritionPanel.users.checkEmail');
        Route::get('/edit-user-quick/{id}', [Controllers\NutritionPanel\UserController::class, 'editUserQuick'])->name('nutritionPanel.users.editUserQuick');
        Route::post('/update-user-quick/{id}', [Controllers\NutritionPanel\UserController::class, 'updateUserQuick'])->name('nutritionPanel.users.updateUserQuick');
        Route::get('/add-user-days/{id}', [Controllers\NutritionPanel\UserController::class, 'addUserDays'])->name('nutritionPanel.users.addUserDays');
        Route::post('/update-user-days/{id}', [Controllers\NutritionPanel\UserController::class, 'updateUserDays'])->name('nutritionPanel.users.updateUserDays');
        Route::get('/subtract-user-days/{id}', [Controllers\NutritionPanel\UserController::class, 'subtractUserDays'])->name('nutritionPanel.users.subtractUserDays');
        Route::post('/update-subtract-user-days/{id}', [Controllers\NutritionPanel\UserController::class, 'updateSubtractUserDays'])->name('nutritionPanel.users.updateSubtractUserDays');
        Route::get('view-weights/{id}', [Controllers\NutritionPanel\UserController::class, 'viewWeights'])->name('nutritionPanel.users.viewWeights');
        Route::get('/get-view-weights', [Controllers\NutritionPanel\UserController::class, 'getViewWeights'])->name('nutritionPanel.users.getViewWeights');
        Route::any('view-attendance/{id}', [Controllers\NutritionPanel\UserController::class, 'viewAttendence'])->name('nutritionPanel.users.viewAttendance');
        Route::get('/view-weight-image/{id}', [Controllers\NutritionPanel\UserController::class, 'viewWeightImage'])->name('nutritionPanel.users.viewWeightImage');
        Route::get('/details/{id}', [Controllers\NutritionPanel\UserController::class, 'details'])->name('nutritionPanel.users.details');
        Route::get('{type?}', [Controllers\NutritionPanel\UserController::class, 'index'])->name('nutritionPanel.users.index');
    });
    //--------

    // Achievements
    Route::group(['prefix' => 'achievements', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\AchievementController::class, 'index'])->name('nutritionPanel.achievements.index');
        Route::get('/get-achievements', [Controllers\NutritionPanel\AchievementController::class, 'getAchievements'])->name('nutritionPanel.achievements.getAchievements');
        Route::get('/create', [Controllers\NutritionPanel\AchievementController::class, 'create'])->name('nutritionPanel.achievements.create');
        Route::post('/', [Controllers\NutritionPanel\AchievementController::class, 'store'])->name('nutritionPanel.achievements.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\AchievementController::class, 'edit'])->name('nutritionPanel.achievements.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\AchievementController::class, 'update'])->name('nutritionPanel.achievements.update');
        Route::post('/change-status', [Controllers\NutritionPanel\AchievementController::class, 'changeStatus'])->name('nutritionPanel.achievements.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\AchievementController::class, 'destroy'])->name('nutritionPanel.achievements.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\AchievementController::class, 'updateOrder'])->name('nutritionPanel.achievements.updateOrder');
    });
    //--------

    // Community Photos
    Route::group(['prefix' => 'community-photos', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\CommunityPhotoController::class, 'index'])->name('nutritionPanel.community-photos.index');
        Route::get('/get-community-photos', [Controllers\NutritionPanel\CommunityPhotoController::class, 'getCommunityPhotos'])->name('nutritionPanel.community-photos.getCommunityPhotos');
        Route::get('/view-photos/{id}', [Controllers\NutritionPanel\CommunityPhotoController::class, 'viewPhotos'])->name('nutritionPanel.community-photos.viewPhotos');
    });
    //--------

    // Activities
    Route::group(['prefix' => 'activities', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\ActivityController::class, 'index'])->name('nutritionPanel.activities.index');
        Route::get('/get-activities', [Controllers\NutritionPanel\ActivityController::class, 'getActivities'])->name('nutritionPanel.activities.getActivities');
        Route::get('/create', [Controllers\NutritionPanel\ActivityController::class, 'create'])->name('nutritionPanel.activities.create');
        Route::post('/', [Controllers\NutritionPanel\ActivityController::class, 'store'])->name('nutritionPanel.activities.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\ActivityController::class, 'edit'])->name('nutritionPanel.activities.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\ActivityController::class, 'update'])->name('nutritionPanel.activities.update');
        Route::post('/change-status', [Controllers\NutritionPanel\ActivityController::class, 'changeStatus'])->name('nutritionPanel.activities.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\ActivityController::class, 'destroy'])->name('nutritionPanel.activities.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\ActivityController::class, 'updateOrder'])->name('nutritionPanel.activities.updateOrder');
    });
    //--------

    // Testimonials
    Route::group(['prefix' => 'testimonials', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\TestimonialController::class, 'index'])->name('nutritionPanel.testimonials.index');
        Route::get('/get-testimonials', [Controllers\NutritionPanel\TestimonialController::class, 'getTestimonials'])->name('nutritionPanel.testimonials.getTestimonials');
        Route::get('/create', [Controllers\NutritionPanel\TestimonialController::class, 'create'])->name('nutritionPanel.testimonials.create');
        Route::post('/', [Controllers\NutritionPanel\TestimonialController::class, 'store'])->name('nutritionPanel.testimonials.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\TestimonialController::class, 'edit'])->name('nutritionPanel.testimonials.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\TestimonialController::class, 'update'])->name('nutritionPanel.testimonials.update');
        Route::post('/change-status', [Controllers\NutritionPanel\TestimonialController::class, 'changeStatus'])->name('nutritionPanel.testimonials.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\TestimonialController::class, 'destroy'])->name('nutritionPanel.testimonials.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\TestimonialController::class, 'updateOrder'])->name('nutritionPanel.testimonials.updateOrder');
    });
    //--------

    // Tips
    Route::group(['prefix' => 'tips', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\TipController::class, 'index'])->name('nutritionPanel.tips.index');
        Route::get('/get-tips', [Controllers\NutritionPanel\TipController::class, 'getTips'])->name('nutritionPanel.tips.getTips');
        Route::get('/create', [Controllers\NutritionPanel\TipController::class, 'create'])->name('nutritionPanel.tips.create');
        Route::post('/', [Controllers\NutritionPanel\TipController::class, 'store'])->name('nutritionPanel.tips.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\TipController::class, 'edit'])->name('nutritionPanel.tips.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\TipController::class, 'update'])->name('nutritionPanel.tips.update');
        Route::post('/change-status', [Controllers\NutritionPanel\TipController::class, 'changeStatus'])->name('nutritionPanel.tips.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\TipController::class, 'destroy'])->name('nutritionPanel.tips.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\TipController::class, 'updateOrder'])->name('nutritionPanel.tips.updateOrder');
        Route::get('/view-video/{id}', [Controllers\NutritionPanel\TipController::class, 'viewVideo'])->name('nutritionPanel.tips.viewVideo');
    });
    //--------

    // Dish Types
    Route::group(['prefix' => 'dish-types', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\DishTypeController::class, 'index'])->name('nutritionPanel.dish-types.index');
        Route::get('/get-dish-types', [Controllers\NutritionPanel\DishTypeController::class, 'getDishTypes'])->name('nutritionPanel.dish-types.getDishTypes');
        Route::get('/create', [Controllers\NutritionPanel\DishTypeController::class, 'create'])->name('nutritionPanel.dish-types.create');
        Route::post('/', [Controllers\NutritionPanel\DishTypeController::class, 'store'])->name('nutritionPanel.dish-types.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\DishTypeController::class, 'edit'])->name('nutritionPanel.dish-types.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\DishTypeController::class, 'update'])->name('nutritionPanel.dish-types.update');
        Route::post('/change-status', [Controllers\NutritionPanel\DishTypeController::class, 'changeStatus'])->name('nutritionPanel.dish-types.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\DishTypeController::class, 'destroy'])->name('nutritionPanel.dish-types.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\DishTypeController::class, 'updateOrder'])->name('nutritionPanel.dish-types.updateOrder');
        Route::get('/view-description/{id}', [Controllers\NutritionPanel\DishTypeController::class, 'viewDescription'])->name('nutritionPanel.dish-types.viewDescription');
    });
    //--------

    // Custom Dishes
    Route::group(['prefix' => 'custom-dishes', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\CustomDishController::class, 'index'])->name('nutritionPanel.custom-dishes.index');
        Route::get('/get-custom-dishes', [Controllers\NutritionPanel\CustomDishController::class, 'getCustomDishes'])->name('nutritionPanel.custom-dishes.getCustomDishes');
        Route::get('/create', [Controllers\NutritionPanel\CustomDishController::class, 'create'])->name('nutritionPanel.custom-dishes.create');
        Route::post('/', [Controllers\NutritionPanel\CustomDishController::class, 'store'])->name('nutritionPanel.custom-dishes.store');
        Route::get('/edit/{id}', [Controllers\NutritionPanel\CustomDishController::class, 'edit'])->name('nutritionPanel.custom-dishes.edit');
        Route::post('/update/{id}', [Controllers\NutritionPanel\CustomDishController::class, 'update'])->name('nutritionPanel.custom-dishes.update');
        Route::post('/change-status', [Controllers\NutritionPanel\CustomDishController::class, 'changeStatus'])->name('nutritionPanel.custom-dishes.changeStatus');
        Route::delete('/destroy', [Controllers\NutritionPanel\CustomDishController::class, 'destroy'])->name('nutritionPanel.custom-dishes.destroy');
        Route::post('/update-order', [Controllers\NutritionPanel\CustomDishController::class, 'updateOrder'])->name('nutritionPanel.custom-dishes.updateOrder');
        Route::get('/view-description/{id}', [Controllers\NutritionPanel\CustomDishController::class, 'viewDescription'])->name('nutritionPanel.custom-dishes.viewDescription');
    });
    //--------

    // Calculate Calories
    Route::group(['prefix' => 'calculate-calories', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\CalculateCalorieController::class, 'index'])->name('nutritionPanel.calculate-calories.index');
    });
    //---------

    // Attendance Register
    Route::group(['prefix' => 'attendance-register', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\AttendenceRegisterController::class, 'index'])->name('nutritionPanel.attendance-register.index');
        Route::get('/get-attendance-register', [Controllers\NutritionPanel\AttendenceRegisterController::class, 'getAttendenceRegister'])->name('nutritionPanel.attendance-register.getAttendanceRegister');
        Route::get('/view-attendance/{id}/{month}/{year}', [Controllers\NutritionPanel\AttendenceRegisterController::class, 'viewAttendence'])->name('nutritionPanel.attendance-register.viewAttendance');
    });
    //--------

    // Shake Intakes
    Route::group(['prefix' => 'shake-intakes', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\ShakeIntakeController::class, 'index'])->name('nutritionPanel.shake-intakes.index');
        Route::get('/get-shake-intakes', [Controllers\NutritionPanel\ShakeIntakeController::class, 'getShakeIntakes'])->name('nutritionPanel.shake-intakes.getShakeIntakes');
    });
    //--------

    // Bmi Calculator
    Route::group(['prefix' => 'bmi-calculator', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index', [Controllers\NutritionPanel\BmiCalculatorController::class, 'index'])->name('nutritionPanel.bmi-calculator.index');
        Route::get('/get-bmi-calculator', [Controllers\NutritionPanel\BmiCalculatorController::class, 'getBmiCalculator'])->name('nutritionPanel.bmi-calculator.getBmiCalculator');
        Route::get('/create', [Controllers\NutritionPanel\BmiCalculatorController::class, 'create'])->name('nutritionPanel.bmi-calculator.create');
        Route::post('/', [Controllers\NutritionPanel\BmiCalculatorController::class, 'store'])->name('nutritionPanel.bmi-calculator.store');
        Route::delete('/destroy', [Controllers\NutritionPanel\BmiCalculatorController::class, 'destroy'])->name('nutritionPanel.bmi-calculator.destroy');
    });
    //--------

    // Counsellings
    Route::group(['prefix' => 'counsellings', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\CounsellingController::class, 'index'])->name('nutritionPanel.counsellings.index');
        Route::get('/get-counsellings', [Controllers\NutritionPanel\CounsellingController::class, 'getCounsellings'])->name('nutritionPanel.counsellings.getCounsellings');
    });
    //--------

    // Orders
    Route::group(['prefix' => 'orders', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\OrderController::class, 'index'])->name('nutritionPanel.orders.index');
        Route::get('/get-orders', [Controllers\NutritionPanel\OrderController::class, 'getOrders'])->name('nutritionPanel.orders.getOrders');
        Route::get('/details/{id}', [Controllers\NutritionPanel\OrderController::class, 'getOrderDetails'])->name('nutritionPanel.orders.getOrderDetails');
        Route::any('/change-status', [Controllers\NutritionPanel\OrderController::class, 'changeStatus'])->name('nutritionPanel.orders.changeStatus');
        Route::any('/payment-status-change', [Controllers\NutritionPanel\OrderController::class, 'paymentStatusChange'])->name('nutritionPanel.orders.paymentStatusChange');

        // Route::get('/add-orders', [Controllers\NutritionPanel\OrderController::class, 'addOrder'])->name('nutritionPanel.orders.addOrder');
        // Route::post('/store-orders', [Controllers\NutritionPanel\OrderController::class, 'storeOrder'])->name('nutritionPanel.orders.storeOrder');
        // Route::get('/edit-orders/{id}', [Controllers\NutritionPanel\OrderController::class, 'editOrder'])->name('nutritionPanel.orders.editOrder');
        // Route::post('/update-orders/{id}',[Controllers\NutritionPanel\OrderController::class,'updateOrder'])->name('nutritionPanel.orders.updateOrder');
        // Route::get('/view-remark/{id}', [Controllers\NutritionPanel\OrderController::class, 'viewRemark'])->name('nutritionPanel.orders.viewRemark');
    });
    //---------

    // Membership Plans
    Route::group(['prefix' => 'membership-plans', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\MembershipPlanController::class, 'index'])->name('nutritionPanel.membership-plans.index');
        Route::get('/get-membership-plans', [Controllers\NutritionPanel\MembershipPlanController::class, 'getMembershipPlans'])->name('nutritionPanel.membership-plans.getMembershipPlans');
    });
    //--------

    // Manual Attendances
    Route::group(['prefix' => 'manual-attendances', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index/{user_id?}', [Controllers\NutritionPanel\ManualAttendenceController::class, 'index'])->name('nutritionPanel.manual-attendances.manual-attendance');
        Route::get('/get-manual-attendances', [Controllers\NutritionPanel\ManualAttendenceController::class, 'getManualAttendence'])->name('nutritionPanel.manual-attendances.getManualAttendance');
        Route::post('add-manual-attendance', [Controllers\NutritionPanel\ManualAttendenceController::class, 'addManualAttendence'])->name('nutritionPanel.manual-attendances.addManualAttendance');
        Route::post('add-today-weight', [Controllers\NutritionPanel\ManualAttendenceController::class, 'addTodayWeight'])->name('nutritionPanel.manual-attendances.addTodayWeight');
        Route::delete('/destroy', [Controllers\NutritionPanel\ManualAttendenceController::class, 'destroy'])->name('nutritionPanel.manual-attendances.destroy');
    });
    //--------

    // Track Shake
    Route::group(['prefix' => 'track-shake', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/index/{user_id?}', [Controllers\NutritionPanel\TrackShakeController::class, 'index'])->name('nutritionPanel.track-shake.index');
        Route::get('/get-track-shake', [Controllers\NutritionPanel\TrackShakeController::class, 'getTrackShake'])->name('nutritionPanel.track-shake.getTrackShake');
    });
    //--------

    // Transactions
    Route::group(['prefix' => 'transactions', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\TransactionController::class, 'index'])->name('nutritionPanel.transactions.index');
        Route::get('/get-transactions', [Controllers\NutritionPanel\TransactionController::class, 'getTransactions'])->name('nutritionPanel.transactions.getTransactions');
        Route::get('/add-transactions', [Controllers\NutritionPanel\TransactionController::class, 'addTransaction'])->name('nutritionPanel.transactions.addTransaction');
        Route::post('/store-transactions', [Controllers\NutritionPanel\TransactionController::class, 'storeTransaction'])->name('nutritionPanel.transactions.storeTransaction');
        Route::get('/edit-transactions/{id}', [Controllers\NutritionPanel\TransactionController::class, 'editTransaction'])->name('nutritionPanel.transactions.editTransaction');
        Route::post('/update-transactions/{id}', [Controllers\NutritionPanel\TransactionController::class, 'updateTransaction'])->name('nutritionPanel.transactions.updateTransaction');
        Route::get('/view-remark/{id}', [Controllers\NutritionPanel\TransactionController::class, 'viewRemark'])->name('nutritionPanel.transactions.viewRemark');
    });
    //--------

    // Reviews
    Route::group(['prefix' => 'reviews', 'middleware' => ['checkfranchiseauth']], function () {
        Route::get('/', [Controllers\NutritionPanel\ReviewController::class, 'reviews'])->name('nutritionPanel.reviews.index');
        Route::get('/get-reviews/{id?}', [Controllers\NutritionPanel\ReviewController::class, 'getReviews'])->name('nutritionPanel.reviews.getReviews');
        Route::delete('/destroy', [Controllers\NutritionPanel\ReviewController::class, 'destroy'])->name('nutritionPanel.reviews.destroy');
    });
    //--------
});