<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Carbon\Carbon;
use DB;

class CronjobController extends Controller
{
    /**
     * 10 Days.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function days10(){
        $sentCount = 0;
        $failedCount = 0;

        try {
            $users = User::where('days', '=', 10)->where('role_type', 'user')->where('status', 1)->get();

            foreach ($users as $user) {
                try {
                    $alreadyNotified = Notification::where('user_id', $user->id)
                        ->where('notification_type', 2)
                        ->whereDate('created_at', '=', date('Y-m-d'))
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    $receiverName = !empty($user->name) ? $user->name : 'Member';
                    $title        = 'Expiry Reminder ⏳';
                    $notiMessage  = $receiverName . ', Time check—10 days remaining.';
                    $message      = $notiMessage;
                    $notificationType = 2;

                    Notification::create([
                        'user_id'             => $user->id,
                        'sender_id'           => 0,
                        'data_id'             => null,
                        'notification_title'  => $title,
                        'notification_text'   => $notiMessage,
                        'sender_name'         => 'System',
                        'receiver_name'       => $receiverName,
                        'notification_type'   => $notificationType,
                        'sent_status'         => 1,
                        'status'              => 0,
                    ]);

                    if (!empty($user->fcm_token)) {
                        push_notification(
                            $user->id,
                            $title,
                            $message,
                            0,
                            $notificationType,
                            $user->fcm_token,
                            null,
                            'System',
                            $receiverName,
                            $user->device_os
                        );
                    }

                    $sentCount++;
                } catch (\Throwable $userEx) {
                    $failedCount++;
                    \Log::error("days10 notification error for User #{$user->id}: " . $userEx->getMessage());
                }
            }

            return response()->json([
                '_status'  => true,
                '_message' => "10-days reminder executed. Sent: {$sentCount}, Failed: {$failedCount}",
                '_data'    => ['sent' => $sentCount, 'failed' => $failedCount, 'total' => $users->count()]
            ], 200);

        } catch(\Throwable $e) {
            \Log::error('Cronjob days10 Error: ' . $e->getMessage());
            return response()->json(['_status' => false, '_message' => $e->getMessage(), '_data' => null], 500);
        }
    }

    /**
     * 5 Days.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function days5(){
        $sentCount = 0;
        $failedCount = 0;

        try {
            $users = User::where('days', '=', 5)->where('role_type', 'user')->where('status', 1)->get();

            foreach ($users as $user) {
                try {
                    $alreadyNotified = Notification::where('user_id', $user->id)
                        ->where('notification_type', 3)
                        ->whereDate('created_at', '=', date('Y-m-d'))
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    $receiverName = !empty($user->name) ? $user->name : 'Member';
                    $title        = 'Expiry Reminder ⏳';
                    $notiMessage  = $receiverName . ', A quick update: 5 days left.';
                    $message      = $notiMessage;
                    $notificationType = 3;

                    Notification::create([
                        'user_id'             => $user->id,
                        'sender_id'           => 0,
                        'data_id'             => null,
                        'notification_title'  => $title,
                        'notification_text'   => $notiMessage,
                        'sender_name'         => 'System',
                        'receiver_name'       => $receiverName,
                        'notification_type'   => $notificationType,
                        'sent_status'         => 1,
                        'status'              => 0,
                    ]);

                    if (!empty($user->fcm_token)) {
                        push_notification(
                            $user->id,
                            $title,
                            $message,
                            0,
                            $notificationType,
                            $user->fcm_token,
                            null,
                            'System',
                            $receiverName,
                            $user->device_os
                        );
                    }

                    $sentCount++;
                } catch (\Throwable $userEx) {
                    $failedCount++;
                    \Log::error("days5 notification error for User #{$user->id}: " . $userEx->getMessage());
                }
            }

            return response()->json([
                '_status'  => true,
                '_message' => "5-days reminder executed. Sent: {$sentCount}, Failed: {$failedCount}",
                '_data'    => ['sent' => $sentCount, 'failed' => $failedCount, 'total' => $users->count()]
            ], 200);

        } catch(\Throwable $e) {
            \Log::error('Cronjob days5 Error: ' . $e->getMessage());
            return response()->json(['_status' => false, '_message' => $e->getMessage(), '_data' => null], 500);
        }
    }

    /**
     * 1 Days.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function days1(){
        $sentCount = 0;
        $failedCount = 0;

        try {
            $users = User::where('days', '=', 1)->where('role_type', 'user')->where('status', 1)->get();

            foreach ($users as $user) {
                try {
                    $alreadyNotified = Notification::where('user_id', $user->id)
                        ->where('notification_type', 4)
                        ->whereDate('created_at', '=', date('Y-m-d'))
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    $receiverName = !empty($user->name) ? $user->name : 'Member';
                    $title        = 'Expiry Today ⏳';
                    $notiMessage  = $receiverName . ', One day left in your plan.';
                    $message      = $notiMessage;
                    $notificationType = 4;

                    Notification::create([
                        'user_id'             => $user->id,
                        'sender_id'           => 0,
                        'data_id'             => null,
                        'notification_title'  => $title,
                        'notification_text'   => $notiMessage,
                        'sender_name'         => 'System',
                        'receiver_name'       => $receiverName,
                        'notification_type'   => $notificationType,
                        'sent_status'         => 1,
                        'status'              => 0,
                    ]);

                    if (!empty($user->fcm_token)) {
                        push_notification(
                            $user->id,
                            $title,
                            $message,
                            0,
                            $notificationType,
                            $user->fcm_token,
                            null,
                            'System',
                            $receiverName,
                            $user->device_os
                        );
                    }

                    $sentCount++;
                } catch (\Throwable $userEx) {
                    $failedCount++;
                    \Log::error("days1 notification error for User #{$user->id}: " . $userEx->getMessage());
                }
            }

            return response()->json([
                '_status'  => true,
                '_message' => "1-day reminder executed. Sent: {$sentCount}, Failed: {$failedCount}",
                '_data'    => ['sent' => $sentCount, 'failed' => $failedCount, 'total' => $users->count()]
            ], 200);

        } catch(\Throwable $e) {
            \Log::error('Cronjob days1 Error: ' . $e->getMessage());
            return response()->json(['_status' => false, '_message' => $e->getMessage(), '_data' => null], 500);
        }
    }

    public function mealType()
    {
        try {
    
            $timezone = 'Asia/Kolkata';
            $now = Carbon::now($timezone);
    
            $users = User::with('meal_type')
                ->where('status', 1)
                ->where('role_type', 'user')
                ->get();
    
            foreach ($users as $user) {
    
                if (empty($user->meal_type_id)) {
                    continue;
                }
    
                $mealType = $user->meal_type;
    
                if (!$mealType || empty($mealType->description)) {
                    continue;
                }
    
                $mealDescription = json_decode($mealType->description, true);
    
                // 1 (Mon) – 7 (Sun)
                $currentDay = Carbon::now($timezone)->isoWeekday();
    
                if (!isset($mealDescription[$currentDay])) {
                    continue;
                }
    
                foreach ($mealDescription[$currentDay] as $mealName => $meal) {
    
                    if (empty($meal['time'])) {
                        continue;
                    }
    
                    // ✅ SAFE TIME PARSING
                    $mealTime = Carbon::parse(
                        Carbon::today($timezone)->format('Y-m-d') . ' ' . $meal['time'],
                        $timezone
                    );
    
                    $notifyTime = $mealTime->copy()->subMinutes(5);
    
                    /**
                     * ✅ CRON SAFE WINDOW (1 minute)
                     * Example:
                     * notify = 12:10
                     * cron runs at 12:10:30 → OK
                     */
                    if ($now->lt($notifyTime) || $now->gt($notifyTime->copy()->addMinute())) {
                        continue;
                    }
    
                    // 🔁 DUPLICATE BLOCK
                    $alreadyNotified = Notification::where('user_id', $user->id)
                        ->where('notification_type', 6)
                        ->whereDate('created_at', Carbon::today($timezone))
                        ->where('notification_title', $mealName)
                        ->exists();
    
                    if ($alreadyNotified) {
                        continue;
                    }

                    if($mealName == 'Morning'){
                        $message = 'Good Morning '.$user->name.' 🌞 Time for Breakfast.';
                    } else if($mealName == 'Morning Snacks'){
                        $message = $user->name.' Time for your snack';
                    } else if($mealName == 'Lunch'){
                        $message = $user->name.', Lunch time 🍽️ Eat properly, feel better';
                    } else if($mealName == 'Evening Snack'){
                        $message = $user->name.' - Don’t wait till you’re tired';
                    } else if($mealName == 'Pre-Meal Starter'){
                        $message = $user->name.", Don't skip this before your dinner.";
                    } else if($mealName == 'Dinner'){
                        $message = $user->name.', Its dinner time! Follow your dinner plan for a better tomorrow.';
                    } else {
                        $message = 'Your '.$mealName.' is in 5 minutes.';
                    }
    
                    // 🔔 NOTIFICATION
                    $title   = 'Meal Reminder 🍽️';
    
                    Notification::create([
                        'user_id'            => $user->id,
                        'sender_id'          => 0,
                        'notification_title' => $mealName,
                        'notification_text'  => $message,
                        'sender_name'        => 'System',
                        'receiver_name'      => $user->name,
                        'notification_type'  => 6,
                    ]);
    
                    push_notification(
                        $user->id,
                        $title,
                        $message,
                        0,
                        6,
                        $user->fcm_token,
                        '',
                        'System',
                        $user->name,
                        $user->device_os
                    );
                }
            }
    
        } catch (\Exception $e) {
            \Log::error('Meal cron Error: ' . $e->getMessage());
        }
    
        return response()->json([
            '_status' => true,
            '_message' => 'Meal cron executed'
        ]);
    }

    public function waterNotifications()
    {
        try {

            $timezone = 'Asia/Kolkata';
            $now = Carbon::now($timezone);

            // 🔹 Fixed water reminder times
            $waterSchedule = [
                '08:30' => 'Lets Start the day with a glass of💧',
                '10:00' => 'Time for Water break.',
                '12:00' => 'A quick sip check 💧',
                '14:00' => 'Drink water, feel better.',
                '16:10' => 'Before fatigue hits…take a sip of water.',
                '18:00' => 'Hydration check-in, Take a sip of water.',
                '20:30' => 'One last water reminder.'
            ];

            $users = User::where('status', 1)
                ->where('role_type', 'user')
                ->get();

            foreach ($waterSchedule as $time => $messageText) {

                $notifyTime = Carbon::parse(
                    Carbon::today($timezone)->format('Y-m-d') . ' ' . $time,
                    $timezone
                );

                // ⏱️ Cron-safe window (1 minute)
                if ($now->lt($notifyTime) || $now->gt($notifyTime->copy()->addMinute())) {
                    continue;
                }

                foreach ($users as $user) {

                    // 🔁 Duplicate prevention (same day + same time)
                    $alreadyNotified = Notification::where('user_id', $user->id)
                        ->where('notification_type', 7) // 7 = Water Reminder
                        ->whereDate('created_at', Carbon::today($timezone))
                        ->where('notification_title', $time)
                        ->exists();

                    if ($alreadyNotified) {
                        continue;
                    }

                    $title = 'Water Reminder 💧';
                    $message = $messageText;

                    // 📦 Save notification
                    Notification::create([
                        'user_id'            => $user->id,
                        'sender_id'          => 0,
                        'notification_title' => $time,
                        'notification_text'  => $message,
                        'sender_name'        => 'System',
                        'receiver_name'      => $user->name,
                        'notification_type'  => 7, // Water reminder
                    ]);

                    // 📲 Push
                    push_notification(
                        $user->id,
                        $title,
                        $message,
                        0,
                        7,
                        $user->fcm_token,
                        '',
                        'System',
                        $user->name,
                        $user->device_os
                    );
                }
            }

        } catch (\Exception $e) {
            \Log::error('Water Notification Cron Error: ' . $e->getMessage());
        }

        return response()->json([
            '_status' => true,
            '_message' => 'Water reminder cron executed'
        ]);
    }

    public function pendingNotifications(){
        $sentCount = 0;
        $failedCount = 0;

        try {
            $notifications = Notification::with('user')->where('sent_status', '=', 0)->get();

            foreach ($notifications as $notification) {
                try {
                    $receiverData = $notification->user;
                    if (!$receiverData) {
                        continue;
                    }

                    $receiverName = !empty($receiverData->name) ? $receiverData->name : 'Member';

                    $user_id            = $receiverData->id;
                    $notification_title = $notification['notification_title'];
                    $notification_text  = $notification['notification_text'];
                    $sender_id          = !empty($notification['sender_id']) ? $notification['sender_id'] : 0;
                    $notification_type  = $notification['notification_type'];
                    $platform           = $receiverData->device_os;
                    $fcm_token          = $receiverData->fcm_token;
                    $data_id            = $notification['data_id'] ?? null;
                    $sender_name        = !empty($notification['sender_name']) ? $notification['sender_name'] : 'System';

                    if (!empty($fcm_token)) {
                        push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiverName, $platform);
                    }

                    Notification::where('id', $notification['id'])->update([
                        'sent_status' => 1,
                    ]);

                    $sentCount++;
                } catch (\Throwable $notiEx) {
                    $failedCount++;
                    \Log::error("pendingNotifications error for Notification #{$notification->id}: " . $notiEx->getMessage());
                }
            }

            return response()->json([
                '_status'  => true,
                '_message' => "Pending notifications processed. Sent: {$sentCount}, Failed: {$failedCount}",
                '_data'    => ['sent' => $sentCount, 'failed' => $failedCount, 'total' => $notifications->count()]
            ], 200);

        } catch(\Throwable $e) {
            \Log::error('Cronjob pendingNotifications Error: ' . $e->getMessage());
            return response()->json(['_status' => false, '_message' => $e->getMessage(), '_data' => null], 500);
        }
    }

    public function pendingAmount(){
        $sentCount    = 0;
        $skippedCount = 0;
        $failedCount  = 0;

        try {
            $users = User::where('due_amount', '>', 0)
                ->where('role_type', 'user')
                ->where('status', 1)
                ->get();

            foreach ($users as $user) {
                try {
                    $lastNotification = Notification::where('user_id', $user->id)
                        ->where('notification_type', 10)
                        ->latest('created_at')
                        ->first();

                    if ($lastNotification && $lastNotification->created_at) {
                        $nextAllowedDate = $lastNotification->created_at->copy()->addDays(3);

                        if (now()->lt($nextAllowedDate)) {
                            $skippedCount++;
                            continue; // 3 days have not elapsed yet
                        }
                    }

                    $receiverName = !empty($user->name) ? $user->name : 'Member';
                    $dueAmountFormatted = number_format((float)$user->due_amount, 0);

                    // Notification content
                    $title            = 'Payment Pending Reminder ⏳';
                    $notiMessage      = 'Hello ' . $receiverName . ', your payment of ₹' . $dueAmountFormatted . ' is pending.';
                    $message          = $notiMessage;
                    $notificationType = 10;

                    Notification::create([
                        'user_id'             => $user->id,
                        'sender_id'           => 0,
                        'data_id'             => null,
                        'notification_title'  => $title,
                        'notification_text'   => $notiMessage,
                        'sender_name'         => 'System',
                        'receiver_name'       => $receiverName,
                        'notification_type'   => $notificationType,
                        'sent_status'         => 1,
                        'status'              => 0,
                    ]);

                    if (!empty($user->fcm_token)) {
                        push_notification(
                            $user->id,
                            $title,
                            $message,
                            0,
                            $notificationType,
                            $user->fcm_token,
                            null,
                            'System',
                            $receiverName,
                            $user->device_os
                        );
                    }

                    $sentCount++;
                } catch (\Throwable $userEx) {
                    $failedCount++;
                    \Log::error("Payment pending reminder error for User #{$user->id}: " . $userEx->getMessage());
                }
            }

            return response()->json([
                '_status'  => true,
                '_message' => "Payment reminder cron executed. Sent: {$sentCount}, Skipped: {$skippedCount}, Failed: {$failedCount}",
                '_data'    => [
                    'sent'    => $sentCount,
                    'skipped' => $skippedCount,
                    'failed'  => $failedCount,
                    'total'   => $users->count(),
                ]
            ], 200);

        } catch(\Throwable $e) {
            \Log::error('Cronjob pendingAmount Error: ' . $e->getMessage());

            return response()->json([
                '_status'  => false,
                '_message' => 'Pending amount cron failed: ' . $e->getMessage(),
                '_data'    => null
            ], 500);
        }
    }
}
