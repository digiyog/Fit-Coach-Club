<?php

namespace App\Http\Traits;
use App\Models\NotificationMessage;
use App\Models\Notification;
use PushNotification;
use App\Models\User;
use App\Models\Role;

trait SendPushNotification
{
    /**
	 * Common function to send notification to users
	 */
    private function sendUserPushNotification($notificationData = [])
    {
        // Get users
        $authUser = auth()->user();
        //----------

        $message = null;
        $title = null;
        $notification = null;
        $response = null;

        if (count($notificationData)) {

            $stylaName = isset($notificationData['styla_name']) ? $notificationData['styla_name'] : null;
            $stylistName = isset($notificationData['stylist_name']) ? $notificationData['stylist_name'] : null;
            $salonName = isset($notificationData['salon_name']) ? $notificationData['salon_name'] : null;
            $bookingId = isset($notificationData['booking_id']) ? $notificationData['booking_id'] : null;
            $bookingDate = isset($notificationData['booking_date']) ? $notificationData['booking_date'] : null;
            $bookingStartDateTime = isset($notificationData['booking_start_date_time']) ? $notificationData['booking_start_date_time'] : null;
            $bookingEndDateTime = isset($notificationData['bokking_end_date_time']) ? $notificationData['bokking_end_date_time'] : null;
            $bookingStartDate = isset($notificationData['booking_start_date']) ? $notificationData['booking_start_date'] : null;
            $bookingStartTime = isset($notificationData['booking_start_time']) ? $notificationData['booking_start_time'] : null;
            $bookingEndDate = isset($notificationData['booking_end_date']) ? $notificationData['booking_end_date'] : null;
            $bookingEndTime = isset($notificationData['booking_end_time']) ? $notificationData['booking_end_time'] : null;
            $bookingAmount = isset($notificationData['booking_amount']) ? $notificationData['booking_amount'] : null;
            $superAdminName = isset($notificationData['super_admin_name']) ? $notificationData['super_admin_name'] : null;
            $documentName = isset($notificationData['document_name']) ? $notificationData['document_name'] : null;
            $cancelReason = isset($notificationData['cancel_reason']) ? $notificationData['cancel_reason'] : null;
            $serviceName = isset($notificationData['service_name']) ? $notificationData['service_name'] : null;
            $bookingCustomId = isset($notificationData['booking_custom_id']) ? $notificationData['booking_custom_id'] : null;
            $reminderMinutes = isset($notificationData['reminder_minutes']) ? $notificationData['reminder_minutes'] : null;
            $transferredToStylistName = isset($notificationData['transfer_to_stylist_name']) ? $notificationData['transfer_to_stylist_name'] : null;
            $bookingTempId = isset($notificationData['temp_booking_id']) ? $notificationData['temp_booking_id'] : null;
            $dueRechargeAmount = isset($notificationData['due_recharge_amount']) ? $notificationData['due_recharge_amount'] : null;
            $imageUrl = isset($notificationData['image_url']) ? $notificationData['image_url'] : null;
            $withdrawalRequestId = isset($notificationData['withdrawal_request_id']) ? $notificationData['withdrawal_request_id'] : null;
            $refundRequestId = isset($notificationData['refund_request_id']) ? $notificationData['refund_request_id'] : null;
            $acceptanceRating = isset($notificationData['acceptance_rating']) ? $notificationData['acceptance_rating'] : null;
            $acceptanceRatingDays = isset($notificationData['acceptance_rating_days']) ? $notificationData['acceptance_rating_days'] : null;
            $walletDebitAmount = isset($notificationData['wallet_debit_amount']) ? $notificationData['wallet_debit_amount'] : null;
            $userName = isset($notificationData['user_name']) ? $notificationData['user_name'] : null;
            $documentExpireDate = isset($notificationData['document_expiry_date']) ? $notificationData['document_expiry_date'] : null;
            
            // Get message
            $messageTags = config('notification_constants.notification_tags');
            $notificationMessage = NotificationMessage::where(function ($query) use ($notificationData) {

                if(isset($notificationData['type']) && !empty($notificationData['type'])) {
                    $query->where('type', $notificationData['type']);
                }

                if(isset($notificationData['for']) && !empty($notificationData['for'])) {
                    $query->where('for', $notificationData['for']);
                }

                if(isset($notificationData['language_code']) && !empty($notificationData['language_code'])) {
                    $query->where('language_code', $notificationData['language_code']);
                }

            })->first();

            if(!empty($notificationMessage)) {
                $replaceTags = [
                    $stylaName, $stylistName, $salonName,
                    $bookingId, $bookingDate,
                    $bookingStartDateTime, $bookingEndDateTime, $bookingStartDate,
                    $bookingStartTime, $bookingEndDate, $bookingEndTime, 
                    $bookingAmount, $superAdminName, $documentName,
                    $cancelReason, $serviceName, $userName, $bookingCustomId, $reminderMinutes, $transferredToStylistName, $dueRechargeAmount, $withdrawalRequestId, $refundRequestId, $acceptanceRating, $acceptanceRatingDays, $walletDebitAmount, $documentExpireDate
                ];

                $message = str_replace($messageTags, $replaceTags, $notificationMessage->message);
                $title = $notificationMessage->title;

                // Create notification
                if(!empty($message) && !empty($title)) {
                    $notification = new Notification();
                    $notification->user_id = isset($notificationData['user_id']) ? $notificationData['user_id'] : null;
                    $notification->booking_id = isset($notificationData['booking_id']) ? $notificationData['booking_id'] : null;
                    $notification->temp_booking_id = isset($notificationData['temp_booking_id']) ? $notificationData['temp_booking_id'] : null;
                    $notification->title = $title;
                    $notification->message = $message;
                    $notification->image   = $imageUrl;
                    $notification->notification_type = isset($notificationData['notification_type']) ? $notificationData['notification_type'] : null;
                    $notification->send_to = isset($notificationData['send_to']) ? $notificationData['send_to'] : null;
                    $notification->send_at = isset($notificationData['sent_at']) ? $notificationData['sent_at'] : now();
                    $notification->send_by = isset($notificationData['send_by']) ? $notificationData['send_by'] : null;
                    $notification->read_status = 'unread';
                    $notification->created_by = $authUser ? $authUser->id : null;
                    $notification->updated_by = $authUser ? $authUser->id : null;

                    if(isset($notificationData['send_at'])){
                        $notification->created_at = isset($notificationData['send_at']) ? $notificationData['send_at'] : now();
                        $notification->updated_at = isset($notificationData['send_at']) ? $notificationData['send_at'] : now();
                    }

                    $notification->save();

                    // Send Notification
                    if(!empty(!empty($notification) && isset($notificationData['user_fcm_token']) && !empty($notificationData['user_fcm_token']))){

                        if(env("NOTIFICATION_TYPE") == 'FCM') {
                            $notiArray = array(
                                'notification' => [
                                    'title' => $title,
                                    'body'  => $message,
                                ],
                                'data' => [
                                    'click_action'      => 'FLUTTER_NOTIFICATION_CLICK',
                                    'notification_type' => $notification['notification_type'],
                                    'user_id'           => $notification['user_id'],
                                    'booking_id'        => $notification['booking_id'],
                                    'temp_booking_id'   => $notification['temp_booking_id'],
                                ]
                            );
                            
                            $pushResponse = PushNotification::setService('fcm')
                            ->setMessage($notiArray)
                            ->setApiKey(env("FCM_SERVER_KEY"))
                            ->setDevicesToken($notificationData['user_fcm_token'])
                            ->send()
                            ->getFeedback();
                        }
                        
                        return $response = 'true';
                    } else {
                        return $response = '';
                    }
                    //------------------
                }
                //--------------------
            } else {
                return '';
            }
            //------------
            
        } else {
            return '';
        }

    }

    /**
	 * Common function to send notification to selected users by admin
	 */
    private function sendUserPushNotificationAdminSelected($notificationData = [])
    {
        // Get users
        $authUser = auth()->user();
        //----------

        $message = null;
        $title = null;
        $notification = null;
        $response = null;
        $urlLink = null;

        if (count($notificationData)) {
            
            $message = $notificationData['message'];
            $title = $notificationData['title'];
            $users = $notificationData['users'];
            $urlLink = $notificationData['url_link'];
            $image = $notificationData['image'] ?? null;

            if(count($users) > 0 && !empty($message) && !empty($title)){

                // Define fcm token blank array and assign in loop below
                $userFcmTokens = [];

                for($i = 0; $i < count($users); $i++)
                {
                    // Get user detail
                    $userDetail = User::select('id', 'role_name', 'fcm_token')
                    ->whereNotNull('fcm_token')
                    ->where('id', $users[$i])->first();

                    if(!(empty($userDetail))){
                        // Create fcm token array
                        $userFcmTokens[] = $userDetail->fcm_token;

                        // Create notification
                        $notification = new Notification();
                        $notification->user_id = $users[$i] ?? null;
                        $notification->booking_id = null;
                        $notification->title = $title;
                        $notification->message = $message;
                        $notification->notification_type = 'OTHER';
                        $notification->send_to = isset($userDetail->role_name) ? $userDetail->role_name : null;
                        $notification->send_at = isset($notificationData['sent_at']) ? $notificationData['sent_at'] : now();
                        $notification->send_by = $authUser ? $authUser->id : null;
                        $notification->read_status = 'unread';
                        $notification->created_by = $authUser ? $authUser->id : null;
                        $notification->updated_by = $authUser ? $authUser->id : null;
                        $notification->url_link = $urlLink;
                        $notification->image = $image;
                        $notification->save();
                    }
                }

                // Send Notification
                if(!(empty($userFcmTokens))){

                    if(env("NOTIFICATION_TYPE") == 'FCM') {
                        $notiArray = array(
                            'notification' => [
                                'title' => $title,
                                'body'  => $message,
                                'image'  => $image,
                            ],
                            'data' => [
                                'click_action'      => 'FLUTTER_NOTIFICATION_CLICK',
                                'notification_type' => 'OTHER',
                                'user_id'           => null,
                                'booking_id'        => null,
                            ]
                        );
                        
                        $pushResponse = PushNotification::setService('fcm')
                        ->setMessage($notiArray)
                        ->setApiKey(env("FCM_SERVER_KEY"))
                        ->setDevicesToken($userFcmTokens)
                        ->send()
                        ->getFeedback();
                    }
                    
                    return $response = 'true';
                } 
                else {
                    return $response = '';
                }
                //------------------
            }
        } 
        else {
            return '';
        }

    }

    /**
	 * Common function to send notification to selected users by admin
	 */
    private function sendUserPushNotificationAdminAll($notificationData = [])
    {
        // Get users
        $authUser = auth()->user();
        //----------

        $message = null;
        $title = null;
        $notification = null;
        $response = null;

        if (count($notificationData)) {
            
            $message = $notificationData['message'];
            $title = $notificationData['title'];
            $roleId = $notificationData['role_id'];
            $urlLink = $notificationData['url_link'];
            $image = $notificationData['image'] ?? null;

            if(!empty($roleId) && !empty($message) && !empty($title)){

                // Get role
                $roleDetail = Role::select('type', 'name', 'staging_topic', 'production_topic', 'dev_topic')->where('id', $roleId)->first();

                // Create notification
                $notification = new Notification();
                $notification->user_id = null;
                $notification->booking_id = null;
                $notification->title = $title;
                $notification->message = $message;
                $notification->notification_type = 'OTHER';
                $notification->send_to = 'All '.$roleDetail->name;
                $notification->send_at = isset($notificationData['sent_at']) ? $notificationData['sent_at'] : now();
                $notification->send_by = $authUser ? $authUser->id : null;
                $notification->read_status = 'unread';
                $notification->created_by = $authUser ? $authUser->id : null;
                $notification->updated_by = $authUser ? $authUser->id : null;
                $notification->url_link = $urlLink;
                $notification->image = $image;
                $notification->save();

                // Send Notification
                if(!(empty($roleDetail))){

                    if(env("NOTIFICATION_TYPE") == 'FCM') {
                        $notiArray = array(
                            'notification' => [
                                'title' => $title,
                                'body'  => $message,
                                'image'  => $image,
                            ],
                            'data' => [
                                'click_action'      => 'FLUTTER_NOTIFICATION_CLICK',
                                'notification_type' => 'OTHER',
                                'user_id'           => null,
                                'booking_id'        => null,
                            ]
                        );
                        
                        $topic = $roleDetail->staging_topic;
                        
                        if(env("NOTIFICATION_TOPIC_TYPE") == 'PRODUCTION') {
                            $topic = $roleDetail->production_topic;
                        }
                        else if(env("NOTIFICATION_TOPIC_TYPE") == 'DEVELOPMENT') {
                            $topic = $roleDetail->dev_topic;
                        }
                        else{
                            $topic = $roleDetail->staging_topic;
                        }
                        
                        if(!empty($topic)){
                            $pushResponse = PushNotification::setService('fcm')
                            ->setMessage($notiArray)
                            ->setApiKey(env("FCM_SERVER_KEY"))
                            ->sendByTopic($topic)
                            ->getFeedback();
                        }
                    }
                    
                    return $response = 'true';
                } 
                else {
                    return $response = '';
                }
                //------------------
            }
        } 
        else {
            return '';
        }

    }

}
