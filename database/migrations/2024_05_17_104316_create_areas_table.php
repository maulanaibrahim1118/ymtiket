<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('areas')->insert([
            ['name' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'head office', 'updated_by' => 'maulana ibrahim'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('areas');
    }
}