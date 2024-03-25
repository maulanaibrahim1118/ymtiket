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
            $table->string('no_ticket', 9)->unique();
            $table->string('kendala', 60);
            $table->text('detail_kendala');
            $table->bigInteger('asset_id');
            $table->bigInteger('user_id');
            $table->bigInteger('client_id');
            $table->bigInteger('location_id');
            $table->bigInteger('agent_id');
            $table->enum('sub_divisi_agent', ['none', 'helpdesk', 'hardware maintenance', 'infrastructur networking', 'tech support']);
            $table->string('file')->nullable();
            $table->enum('role', ['service desk', 'agent']);
            $table->enum('status', ['created', 'onprocess', 'pending', 'resolved', 'finished', 'deleted']);
            $table->string('process_at')->nullable();
            $table->string('pending_at')->nullable();
            $table->bigInteger('pending_time')->nullable();
            $table->bigInteger('processed_time')->nullable();
            $table->string('closed_status')->nullable();
            $table->enum('is_queue', ['ya', 'tidak']);
            $table->enum('assigned', ['ya', 'tidak']);
            $table->enum('need_approval', ['ya', 'tidak']);
            $table->string('approved')->nullable();
            $table->enum('jam_kerja', ['ya', 'tidak']);
            $table->string('ticket_for', 50);
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