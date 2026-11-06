<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GlobalApiErrorHandlingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('email')->unique();
                $table->string('password')->nullable();
                $table->string('role_type')->default('user');
                $table->unsignedInteger('created_by')->nullable();
                $table->tinyInteger('status')->default(1);
                $table->timestamp('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('personal_access_tokens')) {
            Schema::create('personal_access_tokens', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->morphs('tokenable');
                $table->string('name');
                $table->string('token', 64)->unique();
                $table->text('abilities')->nullable();
                $table->timestamp('last_used_at')->nullable();
                $table->timestamps();
            });
        }

        User::truncate();
    }

    /**
     * 1. 404 Resource/API Not Found returns standard JSON
     */
    public function test_404_not_found_returns_standard_json()
    {
        $response = $this->getJson('/api/non-existent-route-endpoint');

        $response->assertStatus(404)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Resource not found',
            ]);
    }

    /**
     * 2. 401 Unauthenticated returns standard JSON
     */
    public function test_401_unauthenticated_returns_standard_json()
    {
        $response = $this->postJson('/api/rating/add', [
            'rating' => '4.0',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Invalid or expired authentication token',
            ]);
    }

    /**
     * 3. 422 Validation Error returns standard JSON
     */
    public function test_422_validation_error_returns_standard_json()
    {
        $response = $this->postJson('/api/app-config', [
            'platform' => 'invalid-platform',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                '_status'  => false,
                '_message' => 'The platform must be either android or ios.',
            ]);
    }

    /**
     * 4. 405 Method Not Allowed returns standard JSON
     */
    public function test_405_method_not_allowed_returns_standard_json()
    {
        // /api/login only accepts POST
        $response = $this->deleteJson('/api/login');

        $response->assertStatus(405)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Method not allowed',
            ]);
    }

    /**
     * 5. Login API credentials check returns "Invalid credentials"
     */
    public function test_login_invalid_email_returns_invalid_credentials()
    {
        $response = $this->postJson('/api/login', [
            'email'    => 'nonexistent@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Invalid credentials',
            ]);
    }

    public function test_login_incorrect_password_returns_invalid_credentials()
    {
        User::create([
            'email'     => 'member@example.com',
            'password'  => Hash::make('correct-password'),
            'role_type' => 'user',
            'status'    => 1,
        ]);

        $response = $this->postJson('/api/login', [
            'email'    => 'member@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Invalid credentials',
            ]);
    }

    /**
     * 6. 500 Internal Server Error never leaks SQL or internal traces
     */
    public function test_500_server_error_returns_safe_json_without_sql_or_trace()
    {
        Route::get('/api/test-server-error-500', function () {
            throw new \Exception('Database query failed: SELECT * FROM secret_table WHERE id = 9999');
        });

        $response = $this->getJson('/api/test-server-error-500');

        $response->assertStatus(500)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Something went wrong',
            ]);

        // Ensure sensitive details are NOT in the response
        $content = $response->getContent();
        $this->assertStringNotContainsString('SELECT * FROM', $content);
        $this->assertStringNotContainsString('secret_table', $content);
        $this->assertStringNotContainsString('Stack trace', $content);
    }

    /**
     * 7. 403 Forbidden returns standard JSON
     */
    public function test_403_forbidden_returns_standard_json()
    {
        Route::get('/api/test-forbidden-403', function () {
            abort(403, 'Access forbidden');
        });

        $response = $this->getJson('/api/test-forbidden-403');

        $response->assertStatus(403)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Access forbidden',
            ]);
    }

    /**
     * 8. 400 Bad Request returns standard JSON
     */
    public function test_400_bad_request_returns_standard_json()
    {
        Route::get('/api/test-bad-request-400', function () {
            abort(400, 'Malformed payload parameters');
        });

        $response = $this->getJson('/api/test-bad-request-400');

        $response->assertStatus(400)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Malformed payload parameters',
            ]);
    }
}
