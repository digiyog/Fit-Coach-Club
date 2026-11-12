<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfig extends Model
{
    protected $table = 'app_configs';

    protected $guarded = ['id'];

    protected $casts = [
        'popup_status'         => 'boolean',
        'android_force_update' => 'boolean',
        'ios_force_update'     => 'boolean',
        'splash_video_enabled' => 'boolean',
        'rating_required'      => 'boolean',
    ];

    /**
     * Get or create the active singleton settings row.
     * Includes a robust fallback if table has not yet been migrated.
     *
     * @return self
     */
    public static function getSettings()
    {
        try {
            $config = self::first();
            if (!$config) {
                $config = self::create([
                    'popup_title'             => '',
                    'popup_message'           => '',
                    'popup_status'            => 1,
                    'android_latest_version'  => '1.0.0',
                    'android_force_update'    => 0,
                    'android_store_url'       => '',
                    'ios_latest_version'      => '1.0.0',
                    'ios_force_update'        => 0,
                    'ios_store_url'           => '',
                    'splash_video_enabled'    => 0,
                    'splash_video_url'        => '',
                    'splash_video_start_date' => null,
                    'splash_video_end_date'   => null,
                    'rating_required'         => 1,
                ]);
            }
            return $config;
        } catch (\Throwable $e) {
            // Safe fallback instance so API never crashes even if table is not yet created
            $fallback = new self();
            $fallback->popup_title             = '';
            $fallback->popup_message           = '';
            $fallback->popup_status            = true;
            $fallback->android_latest_version  = '1.0.0';
            $fallback->android_force_update    = false;
            $fallback->android_store_url       = '';
            $fallback->ios_latest_version      = '1.0.0';
            $fallback->ios_force_update        = false;
            $fallback->ios_store_url           = '';
            $fallback->splash_video_enabled    = false;
            $fallback->splash_video_url        = '';
            $fallback->splash_video_start_date = null;
            $fallback->splash_video_end_date   = null;
            $fallback->rating_required         = true;
            return $fallback;
        }
    }
}
