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
            $table->string('nik', 9)->unique();
            $table->string('nama_agent', 40);
            $table->bigInteger('location_id');
            $table->string('sub_divisi', 40);
            $table->enum('pic_ticket', ['all', 'ho', 'store']);
            $table->enum('status', ['present']);
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('agents')->insert([
            ['nik' => 10030199, 
            'nama_agent' => 'irwan muharamsyah',
            'location_id' => 10,
            'sub_divisi' => 'tech support',
            'pic_ticket' => 'all',
            'status' => 'present',
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
        Schema::dropIfExists('agents');
    }
}