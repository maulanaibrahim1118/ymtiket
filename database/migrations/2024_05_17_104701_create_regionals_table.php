<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regionals', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->bigInteger('area_id');
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('regionals')->insert([
            ['name' => 'distribution center', 'area_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'head office', 'area_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional a', 'area_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional b', 'area_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional c', 'area_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional d', 'area_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional e', 'area_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional f', 'area_id' => 2, 'updated_by' => 'maulana ibrahim'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regionals');
    }
}