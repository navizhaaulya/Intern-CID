<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRoles extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
           $table->bigIncrements('id');
            $table->string('role_code', 255)->unique();
            $table->string('role_type')->nullable();
            $table->string('role_name', 255);
            $table->text('description')->nullable();
            $table->boolean('allow_login')->default(true);
            $table->boolean('active')->default(true);
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestampsTz(0);
        });

        $data = [
    [
        "id" => -1,
        "role_code" => "admin",
        "role_name" => "Admin",
        "role_type" => "internal",
        "description" => "Role untuk administrator sekolah",
        "allow_login" => true,
        "active" => true,
    ],
    [
        "id" => 1,
        "role_code" => "guru",
        "role_name" => "Guru",
        "role_type" => "internal",
        "description" => "Role untuk guru",
        "allow_login" => true,
        "active" => true,
    ],
    [
        "id" => 2,
        "role_code" => "siswa",
        "role_name" => "Siswa",
        "role_type" => "internal",
        "description" => "Role untuk siswa",
        "allow_login" => true,
        "active" => true,
    ],
];

        DB::table('roles')->insert($data);

        DB::statement(
            "SELECT setval('roles_id_seq', (SELECT MAX(id) FROM roles) + 1)"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}