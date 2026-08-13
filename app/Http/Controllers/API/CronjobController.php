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
        try {
            // 10 Days
            $users = User::where('days', '=', 10)->where('role_type', 'user')->where('status', 1)->get();

            $senderData = User::find(0);

            foreach ($users as $user) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('notification_type', 2)
                    ->whereDate('created_at', '=', date('Y-m-d'))
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                // Send Notification
                $receiverData = $user;

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                $daysLeft = 10;
                $title = 'Expiry Reminder ⏳';
                $notiMessage = $receiverData['name'].', Time check—10 days remaining.';
                $message = $receiverData['name'].', Time check—10 days remaining.';
                $notificationType = 2;

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                ]);

                $user_id                = $receiverData->id;
                $notification_title     = $title;
                $notification_text      = $message;
                $sender_id              = $senderData->id;
                $notification_type      = $notificationType;
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);
                //---------
            }

        } catch(\Exception $e) {
            \Log::error('Cronjob Error: ' . $e->getMessage());
            $countries = [];
        }
        //--------------

        // Set response
        if(!empty($countries)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'countries']),
                '_data'    => $countries,
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'countries']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * 5 Days.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function days5(){
        try {
            // 5 Days
            $users = User::where('days', '=', 5)->where('role_type', 'user')->where('status', 1)->get();

            $senderData = User::find(0);

            foreach ($users as $user) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('notification_type', 3)
                    ->whereDate('created_at', '=', date('Y-m-d'))
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                // Send Notification
                $receiverData = $user;

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                $daysLeft = 5;
                $title = 'Expiry Reminder ⏳';
                $notiMessage = $receiverData['name'].', A quick update: 5 days left.';
                $message = $receiverData['name'].', A quick update: 5 days left.';
                $notificationType = 3;

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                ]);

                $user_id                = $receiverData->id;
                $notification_title     = $title;
                $notification_text      = $message;
                $sender_id              = $senderData->id;
                $notification_type      = $notificationType;
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);
                //---------
            }

        } catch(\Exception $e) {
            \Log::error('Cronjob Error: ' . $e->getMessage());
            $countries = [];
        }
        //--------------

        // Set response
        if(!empty($countries)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'countries']),
                '_data'    => $countries,
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'countries']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    /**
     * 1 Days.
     * 
     * @return \Illuminate\Http\Response|\Illuminate\Contracts\Routing\ResponseFactory
     */
    public function days1(){
        try {
            // 1 Days
            $users = User::where('days', '=', 1)->where('role_type', 'user')->where('status', 1)->get();

            $senderData = User::find(0);

            foreach ($users as $user) {
                $alreadyNotified = Notification::where('user_id', $user->id)
                    ->where('notification_type', 4)
                    ->whereDate('created_at', '=', date('Y-m-d'))
                    ->exists();

                if ($alreadyNotified) {
                    continue;
                }

                // Send Notification
                $receiverData = $user;

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                $daysLeft = 1;
                $title = 'Expiry Today ⏳';
                $notiMessage = $receiverData['name'].', One day left in your plan.';
                $message = $receiverData['name'].', One day left in your plan.';
                $notificationType = 4;

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                ]);

                $user_id                = $receiverData->id;
                $notification_title     = $title;
                $notification_text      = $message;
                $sender_id              = $senderData->id;
                $notification_type      = $notificationType;
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);
                //---------
            }

        } catch(\Exception $e) {
            \Log::error('Cronjob Error: ' . $e->getMessage());
            $countries = [];
        }
        //--------------

        // Set response
        if(!empty($countries)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'countries']),
                '_data'    => $countries,
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'countries']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
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
        try {
            $notifications = Notification::with('user')->where('sent_status', '=', 0)->get();

            $senderData = User::find(0);

            foreach ($notifications as $notification) {
                // Send Notification
                $receiverData = $notification->user;

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                $user_id                = $receiverData->id;
                $notification_title     = $notification['notification_title'];
                $notification_text      = $notification['notification_text'];
                $sender_id              = $senderData->id;
                $notification_type      = $notification['notification_type'];
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);

                Notification::where('id', $notification['id'])->update([
                    'sent_status'   => 1,
                ]);
                //---------
            }

        } catch(\Exception $e) {
            \Log::error('Cronjob Error: ' . $e->getMessage());
            $countries = [];
        }
        //--------------

        // Set response
        if(!empty($countries)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'countries']),
                '_data'    => $countries,
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'countries']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }

    public function pendingAmount(){
        try {
            $users = User::where('due_amount', '>', 0)->where('role_type', 'user')->where('status', 1)->get();

            $senderData = User::find(0);

            foreach ($users as $user) {
                $lastNotification = Notification::where('user_id', $user->id)
                    ->where('notification_type', 10)
                    ->latest('created_at')
                    ->first();

                if ($lastNotification) {
                    $nextAllowedDate = $lastNotification->created_at->addDays(3);

                    if (now()->lt($nextAllowedDate)) {
                        continue; // 3 din complete nahi hue
                    }
                }

                // Send Notification
                $receiverData = $user;

                // Set usernames
                $senderData['username']     = $senderData['name'] == '' ? 'Anonymous User' : $senderData['name'];
                $receiverData['username']   = $receiverData['name'] == '' ? 'Anonymous User' : $receiverData['name'];

                // Notification content
                $title = 'Payment Pending Reminder ⏳';
                $notiMessage = 'Hello '.$receiverData['name'].', your payment of ₹'.$receiverData['due_amount'].' is pending.';
                $message = 'Hello '.$receiverData['name'].', your payment of ₹'.$receiverData['due_amount'].' is pending.';
                $notificationType = 10;

                Notification::create([
                    'user_id'             => $receiverData->id,
                    'sender_id'           => $senderData->id,
                    'data_id'             => '',
                    'notification_title'  => $title,
                    'notification_text'   => $notiMessage,
                    'sender_name'         => $senderData['name'],
                    'receiver_name'       => $receiverData['name'],
                    'notification_type'   => $notificationType,
                ]);

                $user_id                = $receiverData->id;
                $notification_title     = $title;
                $notification_text      = $message;
                $sender_id              = $senderData->id;
                $notification_type      = $notificationType;
                $platform               = $receiverData->device_os;
                $fcm_token              = $receiverData->fcm_token;
                $data_id                = '';
                $sender_name            = $senderData['name'];
                $receiver_name          = $receiverData['name'];

                push_notification($user_id, $notification_title, $notification_text, $sender_id, $notification_type, $fcm_token, $data_id, $sender_name, $receiver_name, $platform);
                //---------
            }

        } catch(\Exception $e) {
            \Log::error('Cronjob Error: ' . $e->getMessage());
            $countries = [];
        }
        //--------------

        // Set response
        if(!empty($countries)) {

            $response = [
                '_status'  => true,
                '_message' => __('messages.record_found', ['record' => 'countries']),
                '_data'    => $countries,
            ];
        } else {
            $response = [
                '_status'  => false,
                '_message' => __('messages.record_not_found', ['record' => 'countries']),
                '_data'    => null
            ];
        }
        //-------------

        return response()->json($response, 200);
    }
}
