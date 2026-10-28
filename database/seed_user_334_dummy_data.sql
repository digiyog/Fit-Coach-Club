-- ==============================================================================
-- FitCoachClub - Comprehensive Dummy Data Seed for User ID: 334
-- Generates:
--   1. User Profile (users table)
--   2. Attendance & Weight Progress (attendances table)
--   3. Days Balance & Check-in Ledger (attendance_logs table)
--   4. Payment & Subscription Transactions (transactions table)
--   5. Body Composition & BMI Assessments (bmi_calculator table)
--   6. Nutritional Orders (orders table)
-- ==============================================================================

SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------------------------------
-- 1. CLEAN EXISTING DATA FOR USER 334 (Ensures Idempotency / Safe Re-running)
-- ------------------------------------------------------------------------------
DELETE FROM `attendances` WHERE `user_id` = 334;
DELETE FROM `attendance_logs` WHERE `user_id` = 334;
DELETE FROM `transactions` WHERE `user_id` = 334;
DELETE FROM `bmi_calculator` WHERE `user_id` = 334;
DELETE FROM `orders` WHERE `user_id` = 334;
DELETE FROM `users` WHERE `id` = 334;

-- ------------------------------------------------------------------------------
-- 2. INSERT USER 334 PROFILE
-- ------------------------------------------------------------------------------
INSERT INTO `users` (
    `id`,
    `user_type`,
    `user_state`,
    `name`,
    `email`,
    `email_verified_at`,
    `country_code`,
    `mobile_number`,
    `mobile_number_verified_at`,
    `date_of_birth`,
    `password`,
    `profile_image`,
    `qr_code`,
    `coach_name`,
    `meal_type_id`,
    `product_type_id`,
    `days`,
    `role_id`,
    `role_type`,
    `due_amount`,
    `age`,
    `height`,
    `gender`,
    `starting_weight`,
    `current_weight`,
    `weight_goal`,
    `discount`,
    `start_date`,
    `end_date`,
    `status`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES (
    334,
    'Regular User',
    'Offline',
    'Aarav Patel',
    'aarav.patel334@example.com',
    '2026-10-01 10:00:00',
    '+91',
    '9876543340',
    '2026-10-01 10:00:00',
    '1996-05-15',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- default 'password'
    NULL,
    NULL,
    'Coach Vikram',
    1,
    1,
    18,                 -- 18 remaining days (out of 30 initial plan)
    3,
    'user',
    0,                  -- No dues pending
    28,
    '175',              -- 175 cm
    1,                  -- 1 = Male
    '82.5',             -- Initial Starting Weight
    '76.2',             -- Current Achieved Weight
    '72.0',             -- Target Goal Weight
    '10%',
    '2026-10-01',
    '2026-11-15',
    1,                  -- Active
    1,                  -- Created by Franchise/Admin ID 1
    '2026-10-01 09:30:00',
    NOW()
);

-- ------------------------------------------------------------------------------
-- 3. INSERT ATTENDANCE & WEIGHT CHECK-INS (14 Days of Check-ins)
-- type: 2 = Present, 1 = Absent
-- Demonstrates realistic weight reduction from 79.5 kg down to 76.2 kg
-- ------------------------------------------------------------------------------
INSERT INTO `attendances` (
    `franchise_id`,
    `user_id`,
    `date`,
    `weight`,
    `weight_goal`,
    `weight_image`,
    `message`,
    `type`,
    `order`,
    `status`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES
(1, 334, '2026-10-14', '79.5', '72.0', NULL, 'Morning workout and Shake', 2, 0, 1, 1, '2026-10-14 08:15:00', '2026-10-14 08:15:00'),
(1, 334, '2026-10-15', '79.2', '72.0', NULL, 'Regular Cardio & Nutrition', 2, 0, 1, 1, '2026-10-15 08:20:00', '2026-10-15 08:20:00'),
(1, 334, '2026-10-16', '78.9', '72.0', NULL, 'Strength Training & Shake', 2, 0, 1, 1, '2026-10-16 08:10:00', '2026-10-16 08:10:00'),
(1, 334, '2026-10-17', '78.6', '72.0', NULL, 'High Protein Shake Intake', 2, 0, 1, 1, '2026-10-17 08:30:00', '2026-10-17 08:30:00'),
(1, 334, '2026-10-18', '78.3', '72.0', NULL, 'Cardio and Core Session', 2, 0, 1, 1, '2026-10-18 08:05:00', '2026-10-18 08:05:00'),
(1, 334, '2026-10-19', NULL,   '72.0', NULL, 'Sunday Rest Day',          1, 0, 1, 1, '2026-10-19 09:00:00', '2026-10-19 09:00:00'),
(1, 334, '2026-10-20', '77.9', '72.0', NULL, 'Post Weekend Check-in',    2, 0, 1, 1, '2026-10-20 08:12:00', '2026-10-20 08:12:00'),
(1, 334, '2026-10-21', '77.6', '72.0', NULL, 'Weight Loss Plan Check-in', 2, 0, 1, 1, '2026-10-21 08:18:00', '2026-10-21 08:18:00'),
(1, 334, '2026-10-22', '77.3', '72.0', NULL, 'Manual Attendance Logged',  2, 0, 1, 1, '2026-10-22 08:25:00', '2026-10-22 08:25:00'),
(1, 334, '2026-10-23', '77.0', '72.0', NULL, 'Nutrition & Wellness Shake',2, 0, 1, 1, '2026-10-23 08:10:00', '2026-10-23 08:10:00'),
(1, 334, '2026-10-24', '76.8', '72.0', NULL, 'Circuit Training & Shake',  2, 0, 1, 1, '2026-10-24 08:15:00', '2026-10-24 08:15:00'),
(1, 334, '2026-10-25', '76.5', '72.0', NULL, 'Endurance & Hydration',    2, 0, 1, 1, '2026-10-25 08:22:00', '2026-10-25 08:22:00'),
(1, 334, '2026-10-26', NULL,   '72.0', NULL, 'Rest & Recovery Day',      1, 0, 1, 1, '2026-10-26 09:00:00', '2026-10-26 09:00:00'),
(1, 334, '2026-10-27', '76.2', '72.0', NULL, 'Latest Weigh-in & Shake',  2, 0, 1, 1, '2026-10-27 08:14:00', '2026-10-27 08:14:00');

-- ------------------------------------------------------------------------------
-- 4. INSERT ATTENDANCE LOGS (Days Ledger Reconciled with User Days = 18)
-- Initial plan 30 days - 12 check-ins = 18 remaining days
-- ------------------------------------------------------------------------------
INSERT INTO `attendance_logs` (
    `user_id`,
    `date`,
    `remark`,
    `message`,
    `days`,
    `total_days`,
    `order`,
    `status`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES
(334, '2026-10-01', 'Add User Days',          'Monthly Membership Plan (30 Days)', 30, 30, 0, 1, 1, '2026-10-01 09:30:00', '2026-10-01 09:30:00'),
(334, '2026-10-14', 'Attendance',             'Daily Club Check-in',               1,  29, 0, 1, 1, '2026-10-14 08:15:00', '2026-10-14 08:15:00'),
(334, '2026-10-15', 'Attendance',             'Daily Club Check-in',               1,  28, 0, 1, 1, '2026-10-15 08:20:00', '2026-10-15 08:20:00'),
(334, '2026-10-16', 'Attendance',             'Daily Club Check-in',               1,  27, 0, 1, 1, '2026-10-16 08:10:00', '2026-10-16 08:10:00'),
(334, '2026-10-17', 'Attendance',             'Daily Club Check-in',               1,  26, 0, 1, 1, '2026-10-17 08:30:00', '2026-10-17 08:30:00'),
(334, '2026-10-18', 'Attendance',             'Daily Club Check-in',               1,  25, 0, 1, 1, '2026-10-18 08:05:00', '2026-10-18 08:05:00'),
(334, '2026-10-20', 'Attendance',             'Daily Club Check-in',               1,  24, 0, 1, 1, '2026-10-20 08:12:00', '2026-10-20 08:12:00'),
(334, '2026-10-21', 'Attendance',             'Daily Club Check-in',               1,  23, 0, 1, 1, '2026-10-21 08:18:00', '2026-10-21 08:18:00'),
(334, '2026-10-22', 'Manual Attendance Add',  'Manual check-in marked by Coach',   1,  22, 0, 1, 1, '2026-10-22 08:25:00', '2026-10-22 08:25:00'),
(334, '2026-10-23', 'Attendance',             'Daily Club Check-in',               1,  21, 0, 1, 1, '2026-10-23 08:10:00', '2026-10-23 08:10:00'),
(334, '2026-10-24', 'Attendance',             'Daily Club Check-in',               1,  20, 0, 1, 1, '2026-10-24 08:15:00', '2026-10-24 08:15:00'),
(334, '2026-10-25', 'Attendance',             'Daily Club Check-in',               1,  19, 0, 1, 1, '2026-10-25 08:22:00', '2026-10-25 08:22:00'),
(334, '2026-10-27', 'Attendance',             'Daily Club Check-in',               1,  18, 0, 1, 1, '2026-10-27 08:14:00', '2026-10-27 08:14:00');

-- ------------------------------------------------------------------------------
-- 5. INSERT TRANSACTIONS (Financial / Subscription History)
-- ------------------------------------------------------------------------------
INSERT INTO `transactions` (
    `user_id`,
    `order_id`,
    `title`,
    `total_amount`,
    `due_amount`,
    `received_amount`,
    `payment_type`,
    `remark`,
    `type`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES
(
    334,
    NULL,
    'Monthly Membership Plan (30 Days)',
    3500.00,
    0,
    3500,
    'UPI',
    'Paid via Google Pay - Txn Ref: UPI33400192',
    1,
    1,
    '2026-10-01 09:30:00',
    '2026-10-01 09:30:00'
),
(
    334,
    NULL,
    'Nutritional Supplement & Shake Add-on',
    1200.00,
    0,
    1200,
    'Cash',
    'Club front-desk cash payment',
    1,
    1,
    '2026-10-15 08:35:00',
    '2026-10-15 08:35:00'
);

-- ------------------------------------------------------------------------------
-- 6. INSERT BMI CALCULATOR RECORDS (Progressive Body Composition History)
-- ------------------------------------------------------------------------------
INSERT INTO `bmi_calculator` (
    `user_id`,
    `name`,
    `mobile_number`,
    `age`,
    `weight`,
    `height`,
    `gender`,
    `bmi`,
    `body_fat`,
    `visceral_fat`,
    `muscle_mass`,
    `metabolic_rate`,
    `biologic_age`,
    `body_age`,
    `order`,
    `status`,
    `created_by`,
    `created_at`,
    `updated_at`
) VALUES
(
    334,
    'Aarav Patel',
    '9876543340',
    '28',
    '82.5',
    '175',
    'Male',
    '26.9',
    '24.5',
    '8',
    '58.2',
    '1720',
    '31',
    '31',
    0,
    1,
    1,
    '2026-10-01 10:00:00',
    '2026-10-01 10:00:00'
),
(
    334,
    'Aarav Patel',
    '9876543340',
    '28',
    '79.1',
    '175',
    'Male',
    '25.8',
    '22.1',
    '7',
    '58.9',
    '1705',
    '29',
    '29',
    0,
    1,
    1,
    '2026-10-15 08:45:00',
    '2026-10-15 08:45:00'
),
(
    334,
    'Aarav Patel',
    '9876543340',
    '28',
    '76.2',
    '175',
    'Male',
    '24.9',
    '19.8',
    '6',
    '59.4',
    '1690',
    '27',
    '27',
    0,
    1,
    1,
    '2026-10-27 08:30:00',
    '2026-10-27 08:30:00'
);

-- ------------------------------------------------------------------------------
-- 7. INSERT ORDERS (Nutritional Purchases)
-- ------------------------------------------------------------------------------
INSERT INTO `orders` (
    `franchise_id`,
    `user_id`,
    `order_id`,
    `order_number`,
    `order_date`,
    `product_quantity`,
    `total_amount`,
    `discount`,
    `net_amount`,
    `user_name`,
    `mobile_number`,
    `payment_mode`,
    `payment_status`,
    `order_status`,
    `created_at`,
    `updated_at`
) VALUES
(
    1,
    334,
    1001,
    'ORD-334-8841',
    '2026-10-15',
    2,
    1500.00,
    100,
    1400.00,
    'Aarav Patel',
    '9876543340',
    2, -- Online
    2, -- Success
    6, -- Delivered
    '2026-10-15 11:30:00',
    '2026-10-15 11:30:00'
);

SET FOREIGN_KEY_CHECKS = 1;
