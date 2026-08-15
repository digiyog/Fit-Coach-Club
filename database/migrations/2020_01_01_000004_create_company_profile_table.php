<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyProfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_profile', function (Blueprint $table) {
            $table->id();
            $table->string('header_logo_image')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no', 50)->nullable();
            $table->text('address')->nullable();
            $table->string('facebook_link')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('instagram_link')->nullable();
            $table->string('linkdin_link')->nullable();
            $table->string('whatsapp_no', 50)->nullable();
            $table->string('vimeo_link')->nullable();
            $table->string('from_email')->nullable();
            $table->string('email_password')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_profile');
    }
}