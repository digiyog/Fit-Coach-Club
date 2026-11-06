<?php

namespace App\Http\Controllers\AdminPanel;

use App\Http\Controllers\Controller;
use App\Models\AppConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            'popup_title'            => 'nullable|string|max:255',
            'popup_message'          => 'nullable|string',
            'android_latest_version' => 'required|string|max:50',
            'ios_latest_version'     => 'required|string|max:50',
            'android_store_url'      => 'nullable|url|max:2048',
            'ios_store_url'          => 'nullable|url|max:2048',
        ]);

        try {
            DB::beginTransaction();

            $config = AppConfig::getSettings();

            $data = [
                'popup_title'            => $request->input('popup_title'),
                'popup_message'          => $request->input('popup_message'),
                'popup_status'           => $request->has('popup_status') ? 1 : 0,
                'android_latest_version' => trim($request->input('android_latest_version', '1.0.0')),
                'android_force_update'   => $request->has('android_force_update') ? 1 : 0,
                'android_store_url'      => $request->input('android_store_url'),
                'ios_latest_version'     => trim($request->input('ios_latest_version', '1.0.0')),
                'ios_force_update'       => $request->has('ios_force_update') ? 1 : 0,
                'ios_store_url'          => $request->input('ios_store_url'),
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

