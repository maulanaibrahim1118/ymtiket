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
            $table->bigInteger('nik')->unique();
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
            ['nik' => 23010120, 
            'nama' => 'irwan muharramsyah', 
            'password' => Hash::make('password'),
            'position_id' => 9,
            'location_id' => 10,
            'telp' => '083820326382',
            'ip_address' => '172.17.7.1',
            'role' => 'service desk',
            'updated_by' => 'irwan muharramsyah'
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
