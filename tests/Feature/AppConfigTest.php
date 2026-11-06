<?php

namespace Tests\Feature;

use App\Models\AppConfig;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AppConfigTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create app_configs table for testing in SQLite
        if (!Schema::hasTable('app_configs')) {
            Schema::create('app_configs', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('popup_title')->nullable();
                $table->text('popup_message')->nullable();
                $table->boolean('popup_status')->default(1);
                $table->string('android_latest_version', 50)->default('1.0.0');
                $table->boolean('android_force_update')->default(0);
                $table->text('android_store_url')->nullable();
                $table->string('ios_latest_version', 50)->default('1.0.0');
                $table->boolean('ios_force_update')->default(0);
                $table->text('ios_store_url')->nullable();
                $table->timestamps();
            });
        }

        AppConfig::truncate();
    }

    /**
     * 1. Test app-config API works without authentication
     */
    public function test_app_config_endpoint_works_without_authentication()
    {
        AppConfig::create([
            'popup_title'            => 'Special Offer',
            'popup_message'          => '50% off this weekend',
            'popup_status'           => 1,
            'android_latest_version' => '1.0.7',
            'android_force_update'   => 0,
            'android_store_url'      => 'https://play.google.com/store/apps/details?id=com.fitcoachclub',
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.7',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'          => 'Special Offer',
                    'message'        => '50% off this weekend',
                    'latest_version' => '1.0.7',
                    'force_update'   => 0,
                    'store_url'      => 'https://play.google.com/store/apps/details?id=com.fitcoachclub',
                ],
            ]);
    }

    /**
     * 2. Test app-config handles Bearer null safely without 401
     */
    public function test_app_config_handles_bearer_null_safely()
    {
        AppConfig::create([
            'android_latest_version' => '1.0.0',
            'android_force_update'   => 0,
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer null',
        ])->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.0',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
            ]);
    }

    /**
     * 3. Test validation requires platform
     */
    public function test_app_config_validation_requires_platform()
    {
        $response = $this->postJson('/api/app-config', [
            'version' => '1.0.0',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                '_status' => false,
            ]);
    }

    /**
     * 4. Test validation rejects invalid platform
     */
    public function test_app_config_validation_rejects_invalid_platform()
    {
        $response = $this->postJson('/api/app-config', [
            'platform' => 'windows',
            'version'  => '1.0.0',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                '_status'  => false,
                '_message' => 'The platform must be either android or ios.',
            ]);
    }

    /**
     * 5. TEST 1 from Prompt – Force Update Popup:
     * Config: latest_version = "9.9.9", force_update = 1
     * Installed: "1.0.6"
     * Expected: force_update = 1
     */
    public function test_test_1_force_update_popup()
    {
        AppConfig::create([
            'popup_title'            => '',
            'popup_message'          => '',
            'popup_status'           => 1,
            'android_latest_version' => '9.9.9',
            'android_force_update'   => 1,
            'android_store_url'      => '',
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.6',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'          => '',
                    'message'        => '',
                    'latest_version' => '9.9.9',
                    'force_update'   => 1,
                    'store_url'      => '',
                ],
            ]);
    }

    /**
     * 6. Test version comparison: if user already has latest or newer, force_update must be 0
     */
    public function test_force_update_not_triggered_if_installed_is_latest()
    {
        AppConfig::create([
            'android_latest_version' => '9.9.9',
            'android_force_update'   => 1,
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '9.9.9',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'force_update' => 0,
                ],
            ]);
    }

    /**
     * 7. TEST 2 from Prompt – Dashboard Promotional Popup:
     * Config: title = "Fit Coach Club Special Offer", message = "Enjoy our special offer. Contact us today!",
     *         latest_version = "1.0.6", force_update = 0
     * Installed: "1.0.6"
     */
    public function test_test_2_dashboard_promotional_popup()
    {
        AppConfig::create([
            'popup_title'            => 'Fit Coach Club Special Offer',
            'popup_message'          => 'Enjoy our special offer. Contact us today!',
            'popup_status'           => 1,
            'android_latest_version' => '1.0.6',
            'android_force_update'   => 0,
            'android_store_url'      => '',
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.6',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'          => 'Fit Coach Club Special Offer',
                    'message'        => 'Enjoy our special offer. Contact us today!',
                    'latest_version' => '1.0.6',
                    'force_update'   => 0,
                    'store_url'      => '',
                ],
            ]);
    }

    /**
     * 8. Test popup suppressed when popup_status is 0
     */
    public function test_popup_suppressed_when_status_disabled()
    {
        AppConfig::create([
            'popup_title'            => 'Special Offer',
            'popup_message'          => 'Enjoy our offer',
            'popup_status'           => 0, // Disabled
            'android_latest_version' => '1.0.6',
            'android_force_update'   => 0,
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.6',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'   => '',
                    'message' => '',
                ],
            ]);
    }

    /**
     * 9. Test iOS platform separation
     */
    public function test_ios_platform_configuration_separation()
    {
        AppConfig::create([
            'android_latest_version' => '1.0.6',
            'android_force_update'   => 0,
            'android_store_url'      => 'https://android-store',
            'ios_latest_version'     => '2.0.0',
            'ios_force_update'       => 1,
            'ios_store_url'          => 'https://ios-store',
            'popup_status'           => 1,
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'ios',
            'version'  => '1.0.0',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'latest_version' => '2.0.0',
                    'force_update'   => 1,
                    'store_url'      => 'https://ios-store',
                ],
            ]);
    }
}

