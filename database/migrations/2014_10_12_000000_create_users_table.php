<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('name', 200)->nullable()->index();
            $table->string('username', 200)->nullable()->index();
            $table->string('phone', 200)->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();

            $table->dateTime('created_at')->nullable();
            // $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            // $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->dateTime('deleted_at')->nullable();
            // $table->unsignedBigInteger('deleted_by')->nullable()->index();
            $table->enum('is_active', ['0', '1'])->default('1')->comment('0 = nonactive, 1 = active');
            $table->dateTime('activated_at')->nullable();
            // $table->unsignedBigInteger('activated_by')->nullable()->index();
            $table->dateTime('deactivated_at')->nullable();
            // $table->unsignedBigInteger('deactivated_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
