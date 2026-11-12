<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppConfigController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth.admin');
    }

    /**
     * Display the App Configuration management page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $config = AppConfig::getSettings();

        $breadcrumb = [
            __('language.dashboard') => route('adminPanel.dashboard'),
            'App Configuration'      => '',
        ];

        return view('admin-panel.app-config.index', compact('config', 'breadcrumb'));
    }

    /**
     * Update the App Configuration settings in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'popup_title'             => 'nullable|string|max:255',
            'popup_message'           => 'nullable|string',
            'android_latest_version'  => 'required|string|max:50',
            'ios_latest_version'      => 'required|string|max:50',
            'android_store_url'       => 'nullable|url|max:2048',
            'ios_store_url'           => 'nullable|url|max:2048',
            'splash_video_file'       => 'nullable|file|mimes:mp4|max:15360', // max 15MB MP4
            'splash_video_url'        => 'nullable|url|max:2048',
            'splash_video_start_date' => 'nullable|date',
            'splash_video_end_date'   => 'nullable|date',
        ]);

        try {
            DB::beginTransaction();

            $config = AppConfig::getSettings();

            // Determine Splash Video URL
            $splashVideoUrl = $config->splash_video_url ?? '';

            if ($request->boolean('remove_splash_video')) {
                $splashVideoUrl = '';
            } elseif ($request->hasFile('splash_video_file')) {
                $file = $request->file('splash_video_file');
                $splashDir = public_path('uploads/splash');
                if (!file_exists($splashDir)) {
                    @mkdir($splashDir, 0755, true);
                }
                $fileName = 'splash_' . date('Ymd_His') . '_' . Str::random(6) . '.mp4';
                $file->move($splashDir, $fileName);

                $uploadedUrl = url('uploads/splash/' . $fileName);
                if ($request->isSecure() || str_contains(url('/'), 'fitcoachclub.com') || str_starts_with((string)config('app.url'), 'https://')) {
                    $uploadedUrl = str_replace('http://', 'https://', $uploadedUrl);
                }
                $splashVideoUrl = $uploadedUrl;
            } elseif ($request->filled('splash_video_url')) {
                $splashVideoUrl = trim((string)$request->input('splash_video_url'));
            }

            $startDate = $request->filled('splash_video_start_date')
                ? date('Y-m-d', strtotime((string)$request->input('splash_video_start_date')))
                : null;

            $endDate = $request->filled('splash_video_end_date')
                ? date('Y-m-d', strtotime((string)$request->input('splash_video_end_date')))
                : null;

            $data = [
                'popup_title'             => $request->input('popup_title'),
                'popup_message'           => $request->input('popup_message'),
                'popup_status'            => $request->has('popup_status') ? 1 : 0,
                'android_latest_version'  => trim((string)$request->input('android_latest_version', '1.0.0')),
                'android_force_update'    => $request->has('android_force_update') ? 1 : 0,
                'android_store_url'       => $request->input('android_store_url'),
                'ios_latest_version'      => trim((string)$request->input('ios_latest_version', '1.0.0')),
                'ios_force_update'        => $request->has('ios_force_update') ? 1 : 0,
                'ios_store_url'           => $request->input('ios_store_url'),
                'splash_video_enabled'    => $request->has('splash_video_enabled') ? 1 : 0,
                'splash_video_url'        => $splashVideoUrl,
                'splash_video_start_date' => $startDate,
                'splash_video_end_date'   => $endDate,
                'rating_required'         => $request->has('rating_required') ? 1 : 0,
            ];

            if ($config && $config->exists) {
                $config->update($data);
            } else {
                AppConfig::create($data);
            }

            DB::commit();

            $notification = [
                '_status'  => true,
                '_message' => 'App Configuration has been updated successfully.',
                '_type'    => 'success',
            ];

            return redirect()->route('adminPanel.app-config.index')->with(['notification' => $notification]);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('AppConfig update error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            $notification = [
                '_status'  => false,
                '_message' => 'Failed to update App Configuration. ' . $e->getMessage(),
                '_type'    => 'error',
            ];

            return redirect()->route('adminPanel.app-config.index')->withInput()->with(['notification' => $notification]);
        }
    }
}
