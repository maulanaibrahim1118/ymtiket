<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_approvals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->enum('status', ['null', 'approved', 'rejected']);
            $table->string('approved_by')->nullable();
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('ticket_approvals');
    }
}
