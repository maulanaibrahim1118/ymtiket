<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgressTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->string('tindakan');
            $table->string('process_at');
            $table->enum('status', ['created', 'edited', 'onprocess', 'assigned', 'pending', 'resolved', 'finished', 'deleted']);
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
        Schema::dropIfExists('progress_tickets');
    }
}
