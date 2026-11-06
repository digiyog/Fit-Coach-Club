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
        $installedVersion = trim((string)$request->input('version', '0.0.0'));
        if ($installedVersion === '') {
            $installedVersion = '0.0.0';
        }

        // Compare installed version against latest store version
        $isOlder = version_compare($installedVersion, $latestVersion, '<');

        // If force_update is 1 in DB AND the installed app version is older than latest, force_update = 1
        // Otherwise, allow the user to continue using the app (force_update = 0)
        $forceUpdate = ($isOlder && $forceConfigured === 1) ? 1 : 0;

        // 5. Promotional Popup settings
        $popupStatus  = (bool)($config->popup_status ?? true);
        $popupTitle   = trim((string)($config->popup_title ?? ''));
        $popupMessage = trim((string)($config->popup_message ?? ''));

        // If popup status is turned OFF or both title & message are empty, suppress popup
        if (!$popupStatus || ($popupTitle === '' && $popupMessage === '')) {
            $popupTitle   = '';
            $popupMessage = '';
        }

        return response()->json([
            '_status'  => true,
            '_message' => 'ok',
            'data'     => [
                'title'          => $popupTitle,
                'message'        => $popupMessage,
                'latest_version' => $latestVersion,
                'force_update'   => (int)$forceUpdate,
                'store_url'      => $storeUrl,
            ],
        ], 200);
    }
}

