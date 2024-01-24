<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->bigInteger('sub_category_ticket_id');
            $table->bigInteger('agent_id');
            $table->dateTime('process_at');
            $table->dateTime('pending_at');
            $table->bigInteger('pending_time');
            $table->bigInteger('resolved_time');
            $table->bigInteger('biaya');
            $table->text('note')->nullable();
            $table->enum('status', ['created', 'onprocess', 'pending', 'resolved', 'finished', 'deleted']);
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
        Schema::dropIfExists('ticket_details');
    }
}
