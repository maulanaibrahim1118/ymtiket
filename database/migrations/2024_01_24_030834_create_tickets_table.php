<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('no_ticket', 15)->unique();
            $table->string('kendala', 20);
            $table->text('detail_kendala');
            $table->bigInteger('asset_id');
            $table->bigInteger('user_id');
            $table->bigInteger('client_id');
            $table->string('lokasi_client', 50);
            $table->bigInteger('agent_id');
            $table->enum('role', ['service desk', 'agent']);
            $table->enum('status', ['created', 'onprocess', 'pending', 'resolved', 'finished', 'deleted']);
            $table->string('closed_status')->nullable();
            $table->enum('need_approval', ['ya', 'tidak']);
            $table->enum('jam_kerja', ['ya', 'tidak']);
            $table->string('ticket_for', 3);
            $table->string('ticket_area', 20);
            $table->string('estimated', 30);
            $table->string('updated_by', 40);
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
        Schema::dropIfExists('tickets');
    }
}
