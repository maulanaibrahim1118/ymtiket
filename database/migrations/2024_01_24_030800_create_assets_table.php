<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('no_asset', 15)->unique();
            $table->bigInteger('item_id', 20);
            $table->string('merk', 30);
            $table->string('model', 30);
            $table->string('serial_number', 30);
            $table->enum('status', ['digunakan', 'tidak digunakan']);
            $table->bigInteger('location_id');
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
        Schema::dropIfExists('assets');
    }
}