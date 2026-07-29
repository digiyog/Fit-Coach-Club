<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Global Messages Language
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the app to show the message.
    |
     */

    // Records
    'record_found' => ':Record found.',
    'record_not_found' => 'The :record you are looking for could not be found.',

    'records_found' => ':Records found.',
    'records_not_found' => ':Records not available.',

    'record_created' => ':Record created successfully.',
    'record_creation_failed' => 'Unable to create:record Please try again!.',

    'records_updated' => ':Record updated successfully.',
    'records_updation_failed' => 'Unable to update :record, Please try again!.',

    'records_saved' => ':Records saved successfully.',
    'records_saving_failed' => 'Unable to save :records, Please try again!.',

    'status_changed' => 'Status changed successfully.',
    'status_change_failed' => 'Unable to change status, Please try again!.',

    'record_deleted' => ':Record(s) deleted successfully.',
    'record_failed' => 'Unable to delete :records, Please try again!.',
    'record_deletion_failed' => 'Unable to delete :records, Please try again!.',

    'default_destroy_failed' => 'Default :Records cannot be deleted.',

    'record_import' => ':Record(s) import successfully .',
    'record_import_failed' => 'Unable to import :records, Please try again!.',

    'record_image_deleted' => ':Record image remove successfully.',
    'record_image_deleted_failed' => 'Unable to remove :record image, Please try again!.',

    'record_upload' => ':Record(s) upload successfully .',
    'record_upload_failed' => 'Unable to upload :records, Please try again!.',


    // Success and failure
    'record_success' => ':Record successfully.',
    'record_failed' => 'Unable to :records, Please try again!.',
    //--------------------

    //-------

    //--------

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Messages Language
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the app to show the message.
    |
     */

    // Profile
    'profile_updation_failed' => 'Unable to update profile, Please try again!.',
    'profile_updated' => 'Profile updated successfully.',
    //--------

    // Change Password
    'password_updation_failed' => 'Unable to change password, Please try again!.',
    'password_updated' => 'Password changed successfully.',
    'password_not_matched' => 'Incorrect current password, Please try again!.',
    //--------

    // Image Upload
    'image_uploaded' => 'Image uploaded successfully.',
    'image_uploading_failed' => 'Unable to upload image, Please try again!.',
    //-------------

    // User
    //--------

    // Papers
    'add_section_error' => 'Please select category & paper type before add sections.',
    //-------

    // Package
    'add_category_error' => 'Please select category & package type before add papers.',
    //--------

    /*
    |--------------------------------------------------------------------------
    | API Messages Language
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the app to show the message.
    |
     */

    // Auth
    'account_created' => 'Account created.',
    'account_creation_failed' => 'Unable to create an account, Please try again!',
    'logged_in' => 'Login successfully.',
    'login_failed' => 'Either e-mail address or password is incorrect.',
    'mobile_login_failed' => 'Either mobile number or password is incorrect.',
    'social_login_failed' => 'Unable to login, Please try again!',
    'token_verified' => 'Auth token verified.',
    'account_verified' => 'Account verified',
    'account_verified_failed' => 'Unable to verify account',
    'account_created_but_need_verification' => 'We have sent to you a mail for account verification, Please verify your account by entering given one time password in it.',
    'invalid_one_time_password' => 'You\'ve entered an incorrect OTP, please enter the correct OTP to continue.',
    'logged_out' => 'You have been logged out.',
    'logging_out_failed' => 'Unable to logout, Please try again!',
    'email_or_mobile_number_required' => 'Please enter a valid email address or mobile number.',
    'email_address_not_exists' => 'The email address is not registered, please enter your registered email address.',
    'mobile_number_not_exists' => 'The mobile number is not registered, please enter your registered mobile number.',
    'forgot_password_email_sent' => 'We have sent you a email, Please verify your account by entering given one time password in it.',
    'forgot_password_email_sending_failed' => 'Unable to send forgot password email, Please try again!',
    'forgot_password_sms_sending_failed' => 'Unable to send one time password, Please try again!',
    'email_address_already_exists' => 'The email address is already registered, please enter a different email address.',
    'mobile_number_already_exists' => 'The mobile number is already registered.',
    'username_already_exists' => 'The username is already registered.',
    'one_time_password_required' => 'Please enter a valid one time password.',
    'invalid_one_time_password' => 'You have entered wrong OTP.',
    'invalid_one_time_password_request' => 'Invalid request token.',
    'mobile_number_not_found' => 'The mobile number not found.',
    'otp_creation_failed' => 'Unable to send OTP, Please check your details and please try again!',
    'otp_send_successfully' => 'We have sent you a OTP, Please enter your one time password in it.',
    'account_inactive' => 'Your account has been deactivated. Please contact Support team.',
    'account_pending' => 'Your account has been currently pending for approval.',
    'account_decline' => 'Your account has been Declined. Please contact to support.',
    'onboarding_pending' => 'Please complete your onboarding process.',
    'onboarding_completed' => 'Onboarding completed. Please wait for account approval admin will approve your account as soon as possible',
    'login_failed_country' => 'Your account is not registered from this country.',
    'sms_otp_message' => 'Your verification code is :otp',
    'sms_otp_message_styla_app' => 'Your OTP to register/access the Styla app is :otp.',
    'sms_otp_message_stylist_app' => 'Your OTP to register/access the Styliste app is :otp.',
    //-----

    // User
    'user_found' => 'User record found.',
    'user_not_found' => 'User record not found.',
    'user_notification_success' => 'Notification found.',
    'user_notification_failed' => 'Notification not found.',
    'push_notification_update_success' => 'Push notification status update successfully.',
    'push_notification_update_failed' => 'Unable to update push notification status.',
    'fcm_token_update_success' => 'FCM token update successfully.',
    'fcm_token_update_success' => 'FCM token update successfully.',
    'fcm_token_update_failed' => 'Unable to update FCM token.',
    'referral_code_invalid' => 'Invalid referral code.',
    'referral_code_not_active' => 'Refer And Earns currently not active.',
    'live_location_update_success' => 'Live location updated successfully.',
    'live_location_update_failed' => 'Unable to update live location.',
    'account_deactivate_success' => 'Account deactivated successfully.',
    // 'account_deactivate_failed' => 'Unable to deactivate account, you have an active booking with :status status. Please finish or cancel the booking.',
    'account_deactivate_failed' => 'Unable to delete your account. Please cancel/finish your active bookings.',
    'account_deactivate_failed_salon_staff' => 'Your account has been deactivated. Please contact Salon owner.',
    //-----

    // Verification @author : Pratyush bharti on 22 July 2019
    'verification_mail_subject' => 'Please verify your email address.',
    //-------------------------------------------------------

    // For API Message @author : Pratyush bharti on 31 July 2019
    'user_profile_updated' => 'Your profile has been successfully updated!',
    'user_password_updated' => 'Your password has been successfully updated!',
    'user_password_reset' => 'Your password has been successfully updated, please tap on \'OK\' to continue.',
    'update_user_profile_failed' => 'Unable to update profile, please try again.',
    'old_password_mismatch' => 'You\'ve entered an incorrect old password, please enter the correct one.',
    'something_went_wrong' => 'Oops something went wrong, please try again.',
    'forgot_password_sms_sending_success' => 'One time password has been send to your registered mobile number.',
    //----------------------------------------------------------

    'user_image_updated' => 'Image updated.',
    'user_image_updating_failed' => 'Unable update image, Please try Again!',

    'current_password_mismatch' => 'You\'ve entered an incorrect current password, please enter the correct one.',
    'new_password_is_same' => 'The current password and new password cannot be same.',

    // API Messages
    'email_or_mobile_number_required' => 'Please enter your mobile number.',
    'account_inactive' => 'Your account has been deactivated. Please contact Support team.',
    'otp_send_successfully' => 'We have sent you a OTP, Please enter your one time password in it.',
    'otp_creation_failed' => 'Unable to send OTP, Please check your details and please try again!',
    'platform_or_mobile_number_required' => 'Please enter a valid mobile number and select a platform.',
    'logged_in' => 'You have successfully logged in. Welcome!',
    'account_creation_failed' => 'Unable to create an account, Please try again!',
    'invalid_one_time_password' => "You\'ve entered an incorrect OTP, please enter the correct OTP to continue.",
    'one_time_password_required' => 'Please enter a valid one time password.',
    'social_id_or_social_service_required' => 'Please Enter Your Social ID and Select Your Social Service.',
    'something_went_wrong' => 'Oops something went wrong, please try again.',
    'email_address_already_exists' => 'The email address is already registered, please enter a different email address.',
    'email_address_available' => 'The email address is available.',
    'mobile_number_already_exists' => 'The mobile number is already registered.',
    'mobile_number_available' => 'The mobile number is available.',
    'user_profile_updated' => 'Your profile has been successfully updated!',
    'update_user_profile_failed' => 'Unable to update profile, please try again.',
    'profile_found' => 'Profile found.',
    'profile_not_found' => 'The profile you are looking for could not be found.',
    'language_required' => 'Language Id is required.',
    'language_update_failed' => 'Unable to update language. Please try again!',
    'language_update_success' => 'The language was updated successful.',

    'device_info_update_success' => 'Device information has been successfully updated.',
    'device_info_update_failed' => 'Failed to update device information.',
    
    'notification_status_required' => 'Notification Status is required.',
    'notification_status_update_success' => 'Notification status updated successfully.',
    'notification_status_update_failed' => 'Unable to update notification status. Please try again!',
    
    'account_delete_success' => 'Account deleted successfully.',
    'account_delete_failed' => 'Unable to delete your account.',

    'logged_out' => 'You have been logged out.',
    'logging_out_failed' => 'Unable to logout, Please try again!',

    'record_found' => ':Record found.',
    'record_not_found' => 'The :record you are looking for could not be found.',

    // Place Order
    'place_order_success' => 'Thank you! Your order has been placed successfully.',
    'place_order_failed' => 'Unable to place order. Please try again!',
    // -----------------

    // Order Messages
    'order_found' => 'Order found successfully.',
    'order_not_found' => 'No order found',
    // -----------------

];
