<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nik')->unique();
            $table->string('nama_agent', 40);
            $table->bigInteger('location_id');
            $table->enum('pic_ticket', ['all', 'ho', 'store']);
            $table->enum('status', ['present']);
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('agents')->insert([
            ['nik' => 23010120, 
            'nama_agent' => 'irwan muharramsyah',
            'location_id' => 10,
            'pic_ticket' => 'all',
            'status' => 'present',
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
        Schema::dropIfExists('agents');
    }
}
