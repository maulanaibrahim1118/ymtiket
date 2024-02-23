<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWilayahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wilayahs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('wilayahs')->insert([
            ['name' => 'distribution center'],
            ['name' => 'head office'],
            ['name' => 'Wilayah 1'],
            ['name' => 'Wilayah 2'],
            ['name' => 'Wilayah 3'],
            ['name' => 'Wilayah 4'],
            ['name' => 'Wilayah 5'],
            ['name' => 'Wilayah 6'],
            ['name' => 'Wilayah 7'],
            ['name' => 'Wilayah 8'],
            ['name' => 'Wilayah 9'],
            ['name' => 'Wilayah 10'],
            ['name' => 'Wilayah 11'],
            ['name' => 'Wilayah 12'],
            ['name' => 'Wilayah 13'],
            ['name' => 'Wilayah 14'],
            ['name' => 'Wilayah 15'],
            ['name' => 'Wilayah 16'],
            ['name' => 'Wilayah 17'],
            ['name' => 'Wilayah 18'],
            ['name' => 'Wilayah 19'],
            ['name' => 'Wilayah 20'],
            ['name' => 'Wilayah 21'],
            ['name' => 'Wilayah 22'],
            ['name' => 'Wilayah 23'],
            ['name' => 'Wilayah 24'],
            ['name' => 'Wilayah 25'],
            ['name' => 'Wilayah 26'],
            ['name' => 'Wilayah 27'],
            ['name' => 'Wilayah 28'],
            ['name' => 'Wilayah 29'],
            ['name' => 'Wilayah 30'],
            ['name' => 'Wilayah 31'],
            ['name' => 'Wilayah 32'],
            ['name' => 'Wilayah 33'],
            ['name' => 'Wilayah 34'],
            ['name' => 'Wilayah 35'],
            ['name' => 'Wilayah 36'],
            ['name' => 'Wilayah 37'],
            ['name' => 'Wilayah 38'],
            ['name' => 'Wilayah 39'],
            ['name' => 'Wilayah 40'],
            ['name' => 'Wilayah 41'],
            ['name' => 'Wilayah 42'],
            ['name' => 'Wilayah 43'],
            ['name' => 'Wilayah 44'],
            ['name' => 'Wilayah 45'],
            ['name' => 'Wilayah 46'],
            ['name' => 'Wilayah 47'],
            ['name' => 'Wilayah 48'],
            ['name' => 'Wilayah 49'],
            ['name' => 'Wilayah 50'],
            ['name' => 'Wilayah 51'],
            ['name' => 'Wilayah 52'],
            ['name' => 'Wilayah 53'],
            ['name' => 'Wilayah 54'],
            ['name' => 'Wilayah 55'],
            ['name' => 'Wilayah 56'],
            ['name' => 'Wilayah 57'],
            ['name' => 'Wilayah 58'],
            ['name' => 'Wilayah 59'],
            ['name' => 'Wilayah 60'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wilayahs');
    }
}
