<?php

$constants = [];

/*
|--------------------------------------------------------------------------
| Global constants variables declaration.
|--------------------------------------------------------------------------
|
| Here is we are defining variables for the constants that can be used in
| more than one constants.
|
 */

/**
 * Statuses.
 */
$statuses = [

    'ACTIVE' => [
        'value' => 1,
        'caption' => 'Active',
        'key' => 'ACTIVE',
    ],

    'INACTIVE' => [
        'value' => 0,
        'caption' => 'Inactive',
        'key' => 'INACTIVE',
    ],

];

/**
 * Yes or No.
 */
$yes_or_no = [

    'YES' => [
        'value' => 1,
        'caption' => 'Yes',
        'key' => 'YES',
    ],

    'NO' => [
        'value' => 0,
        'caption' => 'No',
        'key' => 'NO',
    ],

];

/**
 * Images path.
 */
$images_path = 'uploads/images/';

/**
 * Files path.
 */
$files_path = 'uploads/files/';

/*
|--------------------------------------------------------------------------
| Global constants declaration.
|--------------------------------------------------------------------------
|
| Here is we are defining global constants. This constants can be used in
| modules.
|
 */

/**
 * Date formats.
 */
$constants['date_formats'] = [

    'INPUT' => [
        'value' => 'yyyy-mm-dd',
        'caption' => 'yyyy-mm-dd',
        'key' => 'INPUT',
    ],

    'SHOW' => [
        'value' => 'yyyy-mm-dd',
        'caption' => 'yyyy-mm-dd',
        'key' => 'SHOW',
    ],

];

/**
 * Date time formats.
 */
$constants['date_time_formats'] = [

    'INPUT' => [
        'value' => 'yyyy-mm-dd',
        'caption' => 'yyyy-mm-dd',
        'key' => 'INPUT',
    ],

    'SHOW' => [
        'value' => 'Y-m-d H:i:s',
        'caption' => 'Y-m-d H:i:s',
        'key' => 'SHOW',
    ],

];

/**
 * Statuses.
 */
$constants['statuses'] = $statuses;

/**
 * Is default.
 */
$constants['is_default'] = $yes_or_no;

/**
 * Platforms.
 */
$constants['platforms'] = [

    'WEB' => [
        'value' => 'WEB',
        'caption' => 'Web',
        'key' => 'WEB',
    ],

    'ANDROID' => [
        'value' => 'ANDROID',
        'caption' => 'App - Android',
        'key' => 'ANDROID',
    ],

    'IOS' => [
        'value' => 'IOS',
        'caption' => 'App - iOS',
        'key' => 'IOS',
    ],

    // 'ADMIN' => [
    //     'value' => 'ADMIN',
    //     'caption' => 'Admin',
    //     'key' => 'ADMIN',
    // ],

];

/**
 * File systems
 */
$constants['file_systems'] = [

    'S3' => [
        'value' => 's3',
        'caption' => 'S3',
        'key' => 'S3',
    ],

    'LOCAL' => [
        'value' => 'local',
        'caption' => 'Local',
        'key' => 'LOCAL',
    ],

    'PUBLIC' => [
        'value' => 'public',
        'caption' => 'Public',
        'key' => 'PUBLIC',
    ],

];

/*
|--------------------------------------------------------------------------
| Grouped constants declaration.
|--------------------------------------------------------------------------
|
| Here is we are defining constants in grouped format.This constants only
| used by their specific modules.
|
 */

/**
 * Company Profile.
 */
$constants['company_profile'] = [

    // logo path
    'image_path' => $images_path . 'company-profile/',
     // image path thumb
     'image_path_thumb' => $images_path . 'company-profile/thumb/',
     //-----------

];

/**
 * Users.
 */
$constants['users'] = [

     // Types
     'types' => [

        'SUPER_ADMIN' => [
            'value' => 'super-admin',
            'caption' => 'Super Admin',
            'key' => 'SUPER_ADMIN',
        ],
    ],
    //------

    // Image path
    'image_path' => $images_path . 'users/',
    //-----------

    // Files path
    'files_path' => $files_path . 'users/',
    //-----------

    // Image path thumb
    'image_path_thumb' => $images_path . 'users/thumb/',
    //-----------

    // Is default
    'push_notification' => $yes_or_no,
    //-----------

    // Gender
    'gender' => [
        'MALE' => [
            'value' => 1,
            'caption' => 'Male',
            'key' => 'MALE',
        ],

        'FEMALE' => [
            'value' => 2,
            'caption' => 'Female',
            'key' => 'FEMALE',
        ],

        // 'OTHER' => [
        //     'value' => 3,
        //     'caption' => 'Other',
        //     'key' => 'OTHER',
        // ],
    ],
    //------

    // Roles
    'roles' => [
        'SUPER_ADMIN' => [
            'value' => 1,
            'caption' => 'Super Admin',
            'key' => 'SUPER_ADMIN',
            'type' => 'super-admin',
        ],
        'FRANCHISE' => [
            'value' => 2,
            'caption' => 'Franchise',
            'key' => 'FRANCHISE',
            'type' => 'franchise',
        ],
    ],
    //------

    // Account Status
    'account_status' => [
        'PENDING' => [
            'value' => 'PENDING',
            'caption' => 'Pending',
            'key' => 'PENDING',
        ],

        'APPROVE' => [
            'value' => 'APPROVE',
            'caption' => 'Approve',
            'key' => 'APPROVE',
        ],

        'DECLINE' => [
            'value' => 'DECLINE',
            'caption' => 'Decline',
            'key' => 'DECLINE',
        ]
    ],
    //------
];

/**
 * Admin panel email path.
 */
$constants['emails_path'] = [
    'admin_email_path' => 'emails.admin-panel.',
];

/**
 * One Time Password Type.
 */
$constants['one_time_password_types'] = [

    'VERIFICATION' => [
        'value' => 'VERIFICATION',
        'caption' => 'Verification',
        'key' => 'VERIFICATION',
    ],

    'RESET' => [
        'value' => 'RESET',
        'caption' => 'Reset',
        'key' => 'RESET',
    ],

    'MOBILE_VERIFICATION' => [
        'value' => 'MOBILE_VERIFICATION',
        'caption' => 'Mobile Verification',
        'key' => 'MOBILE_VERIFICATION',
    ],

];

/**
 * App configuration.
 */
$constants['app_config_maintenance_mode'] = [

    'ON' => [
        'value' => 1,
        'caption' => 'On',
        'key' => 'ON',
    ],

    'OFF' => [
        'value' => 0,
        'caption' => 'Off',
        'key' => 'OFF',
    ],

];

// Cms Page Types
$constants['page_types'] = [

    'ABOUT_US' => [
        'value' => 'ABOUT',
        'caption' => 'About Us',
        'key' => 'ABOUT_US',
    ],

    'CONTACT' => [
        'value' => 'CONTACT',
        'caption' => 'Contact Us',
        'key' => 'CONTACT_US',
    ],

    'PRIVACY' => [
        'value' => 'PRIVACY',
        'caption' => 'Privacy Policy',
        'key' => 'PRIVACY_POLICY',
    ],

    'TERMS' => [
        'value' => 'TERMS',
        'caption' => 'Terms & Conditions',
        'key' => 'TERMS_AND_CONDITIONS',
    ],

];
//------


/**
 * Pagination Limit.
 */
$constants['pagination_limit'] = [

    // Other Record
    'OTHER_RECORD' => [
        'limit'    => 10,
    ],
    //-----------

];


/**
 * Max Image size in MB.
 */
$constants['max_image_size'] = 3;


/**
 * Enquiry status.
 */
$constants['enquiry_status'] = [

    'PENDING' => [
        'value' => 1,
        'caption' => 'pending',
        'display' => 'Pending',
        'key' => 'PENDING',
    ],

    'COMPLETED' => [
        'value' => 2,
        'caption' => 'completed',
        'display' => 'Completed',
        'key' => 'COMPLETED',
    ],
];

/**
 * Achievements
 */
$constants['achievements'] = [

    // Image path
    'image_path' => $images_path . 'achievements/',
    //-----------

];

/**
 * User Type.
 */
$constants['user_type'] = [
    'Demo User' => [
        'value' => 'Demo User',
        'display' => 'Demo User',
    ],

    '3 Days Trial' => [
        'value' => '3 Days Trial',
        'display' => '3 Days Trial',
    ],

    'Regular User' => [
        'value' => 'Regular User',
        'display' => 'Regular User',
    ],
];

/**
 * User State.
 */
$constants['user_state'] = [
    'Offline/Club Customer' => [
        'value' => 'Offline',
        'display' => 'Offline/Club Customer',
    ],

    'Online Customer' => [
        'value' => 'Online',
        'display' => 'Online Customer',
    ],
];

/**
 * In App Show.
 */
$constants['in_app_show'] = [
    'Yes' => [
        'value' => '1',
        'display' => 'Yes',
    ],

    'No' => [
        'value' => '2',
        'display' => 'No',
    ],
];


/**
 * Show Achievement.
 */
$constants['show_achievement'] = [
    'All User' => [
        'value' => '1',
        'display' => 'All User',
    ],
    'Only Online User' => [
        'value' => '2',
        'display' => 'Only Online User',
    ],
    'Only Offline User' => [
        'value' => '3',
        'display' => 'Only Offline User',
    ],
];

/**
 * Activities
 */
$constants['activities'] = [

    // Image path
    'image_path' => $images_path . 'activities/',
    //-----------

];

/**
 * Activity Type.
 */
$constants['activity_type'] = [
    // 'Old' => [
    //     'value' => '1',
    //     'display' => 'Old Activity',
    // ],

    'Upcoming' => [
        'value' => '2',
        'display' => 'Upcoming Activity',
    ],
];

/**
 * Testimonials
 */
$constants['testimonials'] = [

    // Image path
    'image_path' => $images_path . 'testimonials/',
    //-----------

];

/**
 * Custom Dishes
 */
$constants['custom-dishes'] = [

    // Image path
    'image_path' => $images_path . 'custom-dishes/',
    //-----------

];

/**
 * Payment Type.
 */
$constants['payment_type'] = [
    'Received' => [
        'value' => 'Received',
        'display' => 'Received',
    ],
    'Pending' => [
        'value' => 'Pending',
        'display' => 'Pending',
    ],
];

/**
 * Order status.
 */
$constants['order_status'] = [
    'Order_Placed' => [
        'value' => 1,
        'display' => 'Order Placed',
    ],

    'Ready_Ship' => [
        'value' => 2,
        'display' => 'Ready to Ship',
    ],

    'Return' => [
        'value' => 3,
        'display' => 'Return',
    ],

    'Shipped' => [
        'value' => 4,
        'display' => 'Shipped',
    ],

    'In_Transit' => [
        'value' => 5,
        'display' => 'In Transit',
    ],

    'Delivered' => [
        'value' => 6,
        'display' => 'Delivered',
    ],

    'Cancelled' => [
        'value' => 7,
        'display' => 'Cancelled',
    ],

    'Refund' => [
        'value' => 8,
        'display' => 'Refund',
    ]
];

/**
 * Payment statues.
 */
$constants['payment_statuses'] = [

    'PENDING' => [
        'value' => 1,
        'caption' => 'Pending',
        'display' => 'Pending',
        'key' => 'PENDING',
    ],

    'SUCCESS' => [
        'value' => 2,
        'caption' => 'success',
        'display' => 'Success',
        'key' => 'SUCCESS',
    ],

    'FAILED' => [
        'value' => 3,
        'caption' => 'failed',
        'display' => 'Failed',
        'key' => 'FAILED',
    ],

];

/**
 * Achievement Type.
 */
$constants['achievement_types'] = [
    'Achievement' => [
        'value' => 'Achievement',
        'display' => 'Achievement',
    ],
    'Announcement' => [
        'value' => 'Announcement',
        'display' => 'Announcement',
    ],
];

/**
 * Schedule Types.
 */
$constants['schedule_types'] = [
    'Morning' => [
        'value' => 'Morning',
        'display' => 'Morning',
    ],
    'Morning Snacks' => [
        'value' => 'Morning Snacks',
        'display' => 'Morning Snacks',
    ],
    // 'Breakfast' => [
    //     'value' => 'Breakfast',
    //     'display' => 'Breakfast',
    // ],
    // 'Breakfast Snacks' => [
    //     'value' => 'Breakfast Snacks',
    //     'display' => 'Breakfast Snacks',
    // ],
    'Lunch' => [
        'value' => 'Lunch',
        'display' => 'Lunch',
    ],
    // 'Pre Workout' => [
    //     'value' => 'Pre Workout',
    //     'display' => 'Pre Workout',
    // ],
    // 'Post Workout' => [
    //     'value' => 'Post Workout',
    //     'display' => 'Post Workout',
    // ],
    'Evening Snack' => [
        'value' => 'Evening Snack',
        'display' => 'Evening Snack',
    ],
    'Pre-Meal Starter' => [
        'value' => 'Pre-Meal Starter',
        'display' => 'Pre-Meal Starter',
    ],
    'Dinner' => [
        'value' => 'Dinner',
        'display' => 'Dinner',
    ],
];

/**
 * Communities
 */
$constants['communities'] = [

    // Image path
    'image_path' => $images_path . 'communities/',
    //-----------

];

/**
 * Weights
 */
$constants['weights'] = [

    // Image path
    'image_path' => $images_path . 'weights/',
    //-----------

];

/**
 * Products
 */
$constants['products'] = [

    // Image path
    'image_path' => $images_path . 'products/',
    //-----------

];

/**
 * Meal Types
 */
$constants['meal-types'] = [

    // Image path
    'image_path' => $images_path . 'meal-types/',
    //-----------

];

return $constants;
