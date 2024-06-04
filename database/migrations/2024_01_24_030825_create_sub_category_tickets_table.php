<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoryTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_category_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_sub_kategori', 50);
            $table->bigInteger('category_ticket_id');
            $table->enum('asset_change', ['tidak', 'ya']);
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
        Schema::dropIfExists('sub_category_tickets');
    }
}