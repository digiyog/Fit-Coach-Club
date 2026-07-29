======================================
Super Admin
======================================
1. Dashboard Chart
2. Franchise Details Page
3. User Management
4. Achievements Module
5. Activities Module
Testimonials
Tips (Youtube Links)
Dish Types
Custom Dishes
Meal Types
Calculate Calories
Shake Intakes
Walk and Talk
======================================
======================================
Franchise Admin
======================================
======================================
- Dashboard
- Offline User (Personal Counseling, View Weight, Attendance, Manual Attendence, View Bmi, Track Shake, Special Note, Profit Sharing, Validity)
- Counselling (View Weight, Personal Counseling, Complete, Today Weight, WD, Ttl WD, View Meal, Special Note, Validity)
- Product Management
- Yearly Report
- Quick User
- Demo User (View Weight, Manual Attendence, Update App Password, Track Shake, Yesterday Shake, Day Before Yesterday Shake)
- All User (View Weight, Attendance, Update App Password)
- Communoity Photos
- Migrated Users
- Memership User


1. Counselling Module (Basic Info Add)
2. User Module in action column i have added a dropdown in which new option added "Edit User Quick" for Change Meal Type, Change User Type, Change User State.
3. 2 Point is done on (All User, Offline User, Demo User and Online User) Modules.
4. Substarct User Days and Add User Days option added in user module dropdown (Done in All User, Offline User, Demo User and Online User Modules).
5. Days Pending and Amount Due Added added in All User, Offline User, Demo User and Online User Modules.


attendance_logs
transactions
ALTER TABLE `users` ADD `due_amount` INT NULL DEFAULT '0' AFTER `role_type`;