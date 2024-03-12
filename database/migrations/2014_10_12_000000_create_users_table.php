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
            $table->string('telp', 15);
            $table->string('ip_address', 15);
            $table->enum('role', ['client', 'agent all', 'agent head office', 'agent store', 'service desk']);
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('users')->insert([
            ['nik' => 10030199, 
            'nama' => 'irwan muharamsyah', 
            'password' => Hash::make('password'),
            'position_id' => 9,
            'location_id' => 10,
            'telp' => '0',
            'ip_address' => '0.0.0.0',
            'role' => 'service desk',
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
