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
            $table->string('name', 50)->unique();
            $table->bigInteger('regional_id');
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('wilayahs')->insert([
            ['name' => 'distribution center', 'regional_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'head office', 'regional_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 01', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 02', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 03', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 04', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 05', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 06', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 07', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 08', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 09', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 10', 'regional_id' => 3, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 11', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 12', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 13', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 14', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 15', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 16', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 17', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 18', 'regional_id' => 4, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 19', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 20', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 21', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 22', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 23', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 24', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 25', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 26', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 27', 'regional_id' => 5, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 31', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 32', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 33', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 34', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 35', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 36', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 37', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 38', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 39', 'regional_id' => 6, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 41', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 42', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 43', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 44', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 45', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 46', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 47', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 48', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 49', 'regional_id' => 7, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 51', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 52', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 53', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 54', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 55', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 56', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 57', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 58', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 59', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
            ['name' => 'Wilayah 60', 'regional_id' => 8, 'updated_by' => 'maulana ibrahim'],
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