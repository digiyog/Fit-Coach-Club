<?php

namespace Tests\Feature;

use App\Models\AppConfig;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
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
                $table->boolean('splash_video_enabled')->default(0);
                $table->text('splash_video_url')->nullable();
                $table->date('splash_video_start_date')->nullable();
                $table->date('splash_video_end_date')->nullable();
                $table->boolean('rating_required')->default(1);
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
                    'title'                   => 'Special Offer',
                    'message'                 => '50% off this weekend',
                    'latest_version'          => '1.0.7',
                    'force_update'            => 0,
                    'store_url'               => 'https://play.google.com/store/apps/details?id=com.fitcoachclub',
                    'splash_video_enabled'    => 0,
                    'splash_video_url'        => '',
                    'splash_video_start_date' => '',
                    'splash_video_end_date'   => '',
                    'rating_required'         => 1,
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
     * Config: title = "Happy Diwali", message = "Offer is live till Sunday",
     *         latest_version = "1.0.19", force_update = 0
     * Installed: "1.0.18"
     */
    public function test_test_2_dashboard_promotional_popup()
    {
        AppConfig::create([
            'popup_title'            => 'Happy Diwali',
            'popup_message'          => 'Offer is live till Sunday',
            'popup_status'           => 1,
            'android_latest_version' => '1.0.19',
            'android_force_update'   => 0,
            'android_store_url'      => '',
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.18',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'          => 'Happy Diwali',
                    'message'        => 'Offer is live till Sunday',
                    'latest_version' => '1.0.19',
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

    /**
     * 10. Test full prompt response specification including Splash Video and Rating Required
     */
    public function test_full_specification_splash_video_and_rating()
    {
        AppConfig::create([
            'popup_title'             => 'Happy Diwali',
            'popup_message'           => 'Offer is live till Sunday',
            'popup_status'            => 1,
            'android_latest_version'  => '1.0.19',
            'android_force_update'    => 0,
            'android_store_url'       => '',
            'splash_video_enabled'    => 1,
            'splash_video_url'        => 'https://fitcoachclub.com/public/uploads/splash/diwali2026.mp4',
            'splash_video_start_date' => '2026-11-01',
            'splash_video_end_date'   => '2026-11-12',
            'rating_required'         => 1,
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.18',
        ]);

        $response->assertStatus(200)
            ->assertExactJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'title'                   => 'Happy Diwali',
                    'message'                 => 'Offer is live till Sunday',
                    'latest_version'          => '1.0.19',
                    'force_update'            => 0,
                    'store_url'               => '',
                    'splash_video_enabled'    => 1,
                    'splash_video_url'        => 'https://fitcoachclub.com/public/uploads/splash/diwali2026.mp4',
                    'splash_video_start_date' => '2026-11-01',
                    'splash_video_end_date'   => '2026-11-12',
                    'rating_required'         => 1,
                ],
            ]);
    }

    /**
     * 11. Test rating_required set to 0 returns 0 in app-config
     */
    public function test_rating_required_disabled()
    {
        AppConfig::create([
            'android_latest_version' => '1.0.19',
            'rating_required'        => 0,
        ]);

        $response = $this->postJson('/api/app-config', [
            'platform' => 'android',
            'version'  => '1.0.19',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'ok',
                'data'     => [
                    'rating_required' => 0,
                ],
            ]);
    }
}
