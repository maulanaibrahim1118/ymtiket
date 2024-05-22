<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('nik', 8)->unique();
            $table->string('nama', 40);
            $table->string('password');
            $table->bigInteger('position_id');
            $table->bigInteger('location_id');
            $table->string('telp', 15)->nullable();
            $table->string('ip_address', 15)->nullable();
            $table->string('sub_divisi');
            $table->string('code_access', 15)->nullable();
            $table->bigInteger('role_id');
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['nik' => 10030199,
            'nama' => 'irwan muharamsyah', 
            'password' => Hash::make('password'),
            'position_id' => 9,
            'location_id' => 10,
            'telp' => '2011',
            'ip_address' => '0.0.0.0',
            'sub_divisi' => 'tech support',
            'code_access' => 'all',
            'role_id' => 1,
            'updated_by' => 'maulana ibrahim'
            ]
        ]);
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
}