<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Traits\Orderable;
use App\Http\Traits\Statusable;
use App\Http\Traits\StatusToggleable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class Coach extends Model
{
    use HasFactory, SoftDeletes, Orderable, Statusable, StatusToggleable;

    protected $table = 'coaches';

    protected $fillable = [
        'franchise_id',
        'name',
        'email',
        'mobile_number',
        'profile_image',
        'specialization',
        'experience_years',
        'bio',
        'status',
        'created_by'
    ];

    /**
     * Safely ensures the coaches table exists in the database
     */
    public static function ensureTableExists()
    {
        try {
            if (!Schema::hasTable('coaches')) {
                Schema::create('coaches', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('franchise_id')->nullable()->index();
                    $table->string('name');
                    $table->string('email')->nullable();
                    $table->string('mobile_number')->nullable();
                    $table->string('profile_image')->nullable();
                    $table->string('specialization')->nullable();
                    $table->string('experience_years')->nullable();
                    $table->text('bio')->nullable();
                    $table->tinyInteger('status')->default(1)->comment('1 = Active, 0 = Inactive');
                    $table->unsignedBigInteger('created_by')->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }
        } catch (\Exception $e) {
            \Log::warning('Coach ensureTableExists warning: ' . $e->getMessage());
        }
    }

    /**
     * Franchise relationship
     */
    public function franchise()
    {
        return $this->belongsTo(User::class, 'franchise_id', 'id');
    }

    /**
     * Members assigned to this coach
     */
    public function members()
    {
        return $this->hasMany(User::class, 'coach_name', 'name')
            ->where('role_type', 'user');
    }

    /**
     * Active members assigned to this coach
     */
    public function activeMembers()
    {
        return $this->members()
            ->where('status', 1)
            ->where('days', '>', 0);
    }
}
