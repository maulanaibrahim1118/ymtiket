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
            $table->bigInteger('total_ticket');
            $table->bigInteger('ticket_assigned');
            $table->bigInteger('total_assigned_time');
            $table->bigInteger('total_resolved_time');
            $table->enum('status', ['busy', 'idle']);
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('agents')->insert([
            ['nik' => 23010124, 
            'nama_agent' => 'maulana ibrahim',
            'location_id' => 10,
            'total_ticket' => 0,
            'ticket_assigned' => 0,
            'total_resolved_time' => 0,
            'total_assigned_time' => 0,
            'status' => 'idle',
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
