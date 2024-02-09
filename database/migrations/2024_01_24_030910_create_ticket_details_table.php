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
            $table->string('process_at')->nullable();
            $table->string('pending_at')->nullable();
            $table->bigInteger('pending_time')->nullable();
            $table->bigInteger('processed_time')->nullable();
            $table->bigInteger('biaya')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['onprocess', 'pending', 'resolved', 'assigned']);
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
