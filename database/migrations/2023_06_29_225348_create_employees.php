<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('fullname')->nullable(false);
            $table->string('nik')->nullable(true)->comment('nomor induk karyawan');
            $table->string('nationality_type')->nullable(false)->comment('wna,wni');
            $table->string('identity_number')->nullable(true)->unique();
            $table->string('gender')->nullable(false)->comment('male,female');
            $table->text('address')->nullable(true);
            $table->string('email')->nullable(true)->unique();
            $table->string('telephone')->nullable(true);
            $table->date('birthdate')->nullable(true);
            $table->text('description')->nullable(true);
            $table->text('img_photo')->nullable(true);
            $table->date('join_date')->nullable(true);
            $table->date('resign_date')->nullable(true);
            $table->boolean('active')->nullable(true)->default(true);
            $table->bigInteger('user_id')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestampsTz($precision = 0);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
