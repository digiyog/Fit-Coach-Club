<?php

namespace Tests\Feature;

use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RatingApiTest extends TestCase
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
                $table->timestamps();
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

        if (!Schema::hasTable('ratings')) {
            Schema::create('ratings', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->nullable();
                $table->unsignedInteger('rating_user_id')->nullable();
                $table->decimal('rating', 3, 1)->default(1.0)->nullable();
                $table->text('message')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        Rating::truncate();
        User::truncate();
    }

    /**
     * 1. Unauthenticated request must return 401 JSON
     */
    public function test_rating_unauthenticated_returns_401_json()
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
     * 2. Missing rating returns 422 JSON validation error
     */
    public function test_rating_validation_missing_rating_returns_422_json()
    {
        $user = User::create([
            'email'     => 'test@example.com',
            'role_type' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/rating/add', []);

        $response->assertStatus(422)
            ->assertJson([
                '_status'  => false,
                '_message' => 'Please provide a valid rating',
            ]);
    }

    /**
     * 3. Invalid rating below 1.0 or above 5.0 returns 422
     */
    public function test_rating_validation_out_of_range_returns_422()
    {
        $user = User::create([
            'email'     => 'test2@example.com',
            'role_type' => 'user',
        ]);

        Sanctum::actingAs($user, ['*']);

        // Test below 1.0
        $response1 = $this->postJson('/api/rating/add', ['rating' => '0.5']);
        $response1->assertStatus(422)->assertJson(['_status' => false, '_message' => 'Please provide a valid rating']);

        // Test above 5.0
        $response2 = $this->postJson('/api/rating/add', ['rating' => '5.5']);
        $response2->assertStatus(422)->assertJson(['_status' => false, '_message' => 'Please provide a valid rating']);

        // Test non-numeric
        $response3 = $this->postJson('/api/rating/add', ['rating' => 'excellent']);
        $response3->assertStatus(422)->assertJson(['_status' => false, '_message' => 'Please provide a valid rating']);
    }

    /**
     * 4. Authenticated rating creation success
     */
    public function test_rating_add_success_for_authenticated_user()
    {
        $user = User::create([
            'email'      => 'test3@example.com',
            'role_type'  => 'user',
            'created_by' => 42,
        ]);

        Sanctum::actingAs($user, ['*']);

        $response = $this->postJson('/api/rating/add', [
            'rating'  => '4.0',
            'message' => 'Great experience!',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'Rating submitted successfully',
            ]);

        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'rating'  => '4.0',
            'message' => 'Great experience!',
        ]);
    }

    /**
     * 5. Duplicate rating submission updates existing rating instead of error
     */
    public function test_duplicate_rating_updates_existing_rating_instead_of_failing()
    {
        $user = User::create([
            'email'      => 'test4@example.com',
            'role_type'  => 'user',
            'created_by' => 42,
        ]);

        Sanctum::actingAs($user, ['*']);

        // First rating: 3.5
        $first = $this->postJson('/api/rating/add', [
            'rating'  => '3.5',
            'message' => 'Good',
        ]);

        $first->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'Rating submitted successfully',
            ]);

        $this->assertEquals(1, Rating::where('user_id', $user->id)->count());

        // Second rating submission by same user: 5.0
        $second = $this->postJson('/api/rating/add', [
            'rating'  => '5.0',
            'message' => 'Even better now!',
        ]);

        $second->assertStatus(200)
            ->assertJson([
                '_status'  => true,
                '_message' => 'Rating submitted successfully',
            ]);

        // Still only 1 record for this user, updated to 5.0
        $this->assertEquals(1, Rating::where('user_id', $user->id)->count());
        $this->assertDatabaseHas('ratings', [
            'user_id' => $user->id,
            'rating'  => '5.0',
            'message' => 'Even better now!',
        ]);
    }
}

