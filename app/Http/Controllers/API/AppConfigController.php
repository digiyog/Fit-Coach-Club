<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppConfigController extends Controller
{
    /**
     * Get Mobile App Configuration.
     * Accessible with or without authentication.
     * Safely handles missing tokens and 'Bearer null'.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConfig(Request $request)
    {
        // 1. Validate request parameters
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string',
            'version'  => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                '_status'  => false,
                '_message' => $validator->errors()->first(),
            ], 422);
        }

        $platform = strtolower(trim((string)$request->input('platform')));

        if (!in_array($platform, ['android', 'ios'])) {
            return response()->json([
                '_status'  => false,
                '_message' => 'The platform must be either android or ios.',
            ], 422);
        }

        // 2. Fetch configurations
        $config = AppConfig::getSettings();

        // 3. Platform specific variables
        if ($platform === 'android') {
            $latestVersion   = (string)($config->android_latest_version ?? '1.0.0');
            $forceConfigured = (int)($config->android_force_update ?? 0);
            $storeUrl        = (string)($config->android_store_url ?? '');
        } else {
            $latestVersion   = (string)($config->ios_latest_version ?? '1.0.0');
            $forceConfigured = (int)($config->ios_force_update ?? 0);
            $storeUrl        = (string)($config->ios_store_url ?? '');
        }

        // 4. Semantic version comparison
        $installedVersion = trim((string)$request->input('version', ''));
        if ($installedVersion !== '') {
            $isOlder = version_compare($installedVersion, $latestVersion, '<');
            $forceUpdate = ($isOlder && $forceConfigured === 1) ? 1 : 0;
        } else {
            $forceUpdate = $forceConfigured;
        }

        // 5. Promotional Popup settings
        $popupStatus  = (bool)($config->popup_status ?? true);
        $popupTitle   = trim((string)($config->popup_title ?? ''));
        $popupMessage = trim((string)($config->popup_message ?? ''));

        // If popup status is turned OFF or both title & message are empty, suppress popup
        if (!$popupStatus || ($popupTitle === '' && $popupMessage === '')) {
            $popupTitle   = '';
            $popupMessage = '';
        }

        // 6. Splash Video settings
        $splashVideoEnabled = (int)($config->splash_video_enabled ? 1 : 0);
        $splashVideoUrl     = (string)($config->splash_video_url ?? '');

        $splashStartDate = '';
        if (!empty($config->splash_video_start_date)) {
            $splashStartDate = is_string($config->splash_video_start_date)
                ? substr($config->splash_video_start_date, 0, 10)
                : $config->splash_video_start_date->format('Y-m-d');
        }

        $splashEndDate = '';
        if (!empty($config->splash_video_end_date)) {
            $splashEndDate = is_string($config->splash_video_end_date)
                ? substr($config->splash_video_end_date, 0, 10)
                : $config->splash_video_end_date->format('Y-m-d');
        }

        // 7. Rating Required setting (default: 1)
        $ratingRequired = isset($config->rating_required) ? ((int)$config->rating_required ? 1 : 0) : 1;

        return response()->json([
            '_status'  => true,
            '_message' => 'ok',
            'data'     => [
                'title'                   => $popupTitle,
                'message'                 => $popupMessage,
                'latest_version'          => $latestVersion,
                'force_update'            => (int)$forceUpdate,
                'store_url'               => $storeUrl,
                'splash_video_enabled'    => $splashVideoEnabled,
                'splash_video_url'        => $splashVideoUrl,
                'splash_video_start_date' => $splashStartDate,
                'splash_video_end_date'   => $splashEndDate,
                'rating_required'         => $ratingRequired,
            ],
        ], 200);
    }
}
