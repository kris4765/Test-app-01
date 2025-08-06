<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\DBproftestController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;
use App\Models\User;
use App\Services\FCMService;

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

Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Add more protected routes here
});



Route::apiResource('todos', TodoController::class);
Route::patch('/todos/{id}/toggle-status', [TodoController::class, 'toggleStatus']);


Route::get('/dbproftest', [DBproftestController::class, 'index']);
Route::post('/dbproftest/generate', [DBprofTestController::class, 'generate']);


Route::apiResource('users', UserController::class);



// //notification apis

Route::get('/notifications', [NotificationController::class, 'index']);
Route::post('/notifications', [NotificationController::class, 'store']);
Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
Route::post('/notify-admins-login', [NotificationController::class, 'notifyAdminsOnLogin']);
Route::post('/notify-admins-todo', [NotificationController::class, 'nnotifytodo']);



Route::get('/notifications', function (Request $request) {
    return \App\Models\Notification::where('send_to', $request->email)
        ->orderBy('created_at', 'desc')
        ->get();
});



// Route::post('/save-fcm-token', function (Request $request) {
//     $request->validate(['token' => 'required|string']);
//     $user = auth()->user();
//     $user->update(['fcm_token' => $request->token]); 
//     return response()->json(['message' => $request->token]);
// });



Route::post('/save-fcm-token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'token' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    $user->update(['fcm_token' => $request->token]);

    return response()->json(['message' => 'Token saved successfully']);
});







// Route::post('/test-fcm', function (Request $request, FCMService $fcm) {

//     $request->validate([
//         'email' => 'required|email',
//     ]);

//     $user = User::where('email', $request->email)->first(); // ✅ Fix here

//     if (!$user || !$user->fcm_token) {
//         return response()->json(['error' => 'User or FCM token not found'], 404);
//     }

//     return $fcm->sendNotification($user->fcm_token, 'Hello!', 'This is a test FCM push from Laravel');
// });





Route::post('/test-fcm', function (Request $request, FCMService $fcm) {
    $user = User::where('email', 'admin@example.com')->first();



    if (!$user) {
        return response()->json(['error' => 'User not found','email' => $request->email], 404);
    }

    if (!$user->fcm_token) {
        return response()->json(['error' => 'FCM token missing'], 404);
    }

    // $fcm->sendNotification(
    //     $user->fcm_token,
    //     'Test Notification',
    //     'This is a test FCM push notification from button click.'
    // );

    if($fcm->sendNotification(
        $user->fcm_token,
        'Test Notification',
        'This is a test FCM push notification from button click.'
    )) {
        return response()->json(['message' => 'Notification sent successfully']);
    }

    // return response()->json(['message' => 'Notification sent']);
});
















