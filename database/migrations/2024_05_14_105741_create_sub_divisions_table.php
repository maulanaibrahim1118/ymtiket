<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->bigInteger('location_id');
            $table->string('code_access')->nullable();
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('sub_divisions')->insert([
            ['name' => 'helpdesk', 'location_id' => '10', 'code_access' => 'ho', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'hardware maintenance', 'location_id' => '10', 'code_access' => 'all', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'infrasctructure networking', 'location_id' => '10', 'code_access' => 'store', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'tech support', 'location_id' => '10', 'code_access' => 'store', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'area 1', 'location_id' => '17', 'code_access' => '1', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'area 2', 'location_id' => '17', 'code_access' => '2', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional a', 'location_id' => '17', 'code_access' => 'a', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional b', 'location_id' => '17', 'code_access' => 'b', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional c', 'location_id' => '17', 'code_access' => 'c', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional d', 'location_id' => '17', 'code_access' => 'd', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional e', 'location_id' => '17', 'code_access' => 'e', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'regional f', 'location_id' => '17', 'code_access' => 'f', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 01', 'location_id' => '17', 'code_access' => '01', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 02', 'location_id' => '17', 'code_access' => '02', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 03', 'location_id' => '17', 'code_access' => '03', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 04', 'location_id' => '17', 'code_access' => '04', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 05', 'location_id' => '17', 'code_access' => '05', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 06', 'location_id' => '17', 'code_access' => '06', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 07', 'location_id' => '17', 'code_access' => '07', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 08', 'location_id' => '17', 'code_access' => '08', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 09', 'location_id' => '17', 'code_access' => '09', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 10', 'location_id' => '17', 'code_access' => '10', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 11', 'location_id' => '17', 'code_access' => '11', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 12', 'location_id' => '17', 'code_access' => '12', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 13', 'location_id' => '17', 'code_access' => '13', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 14', 'location_id' => '17', 'code_access' => '14', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 15', 'location_id' => '17', 'code_access' => '15', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 16', 'location_id' => '17', 'code_access' => '16', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 17', 'location_id' => '17', 'code_access' => '17', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 18', 'location_id' => '17', 'code_access' => '18', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 19', 'location_id' => '17', 'code_access' => '19', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 20', 'location_id' => '17', 'code_access' => '20', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 21', 'location_id' => '17', 'code_access' => '21', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 22', 'location_id' => '17', 'code_access' => '22', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 23', 'location_id' => '17', 'code_access' => '23', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 24', 'location_id' => '17', 'code_access' => '24', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 25', 'location_id' => '17', 'code_access' => '25', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 26', 'location_id' => '17', 'code_access' => '26', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 27', 'location_id' => '17', 'code_access' => '27', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 31', 'location_id' => '17', 'code_access' => '31', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 32', 'location_id' => '17', 'code_access' => '32', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 33', 'location_id' => '17', 'code_access' => '33', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 34', 'location_id' => '17', 'code_access' => '34', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 35', 'location_id' => '17', 'code_access' => '35', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 36', 'location_id' => '17', 'code_access' => '36', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 37', 'location_id' => '17', 'code_access' => '37', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 38', 'location_id' => '17', 'code_access' => '38', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 39', 'location_id' => '17', 'code_access' => '39', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 41', 'location_id' => '17', 'code_access' => '41', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 42', 'location_id' => '17', 'code_access' => '42', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 43', 'location_id' => '17', 'code_access' => '43', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 44', 'location_id' => '17', 'code_access' => '44', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 45', 'location_id' => '17', 'code_access' => '45', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 46', 'location_id' => '17', 'code_access' => '46', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 47', 'location_id' => '17', 'code_access' => '47', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 48', 'location_id' => '17', 'code_access' => '48', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 49', 'location_id' => '17', 'code_access' => '49', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 51', 'location_id' => '17', 'code_access' => '51', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 52', 'location_id' => '17', 'code_access' => '52', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 53', 'location_id' => '17', 'code_access' => '53', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 54', 'location_id' => '17', 'code_access' => '54', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 55', 'location_id' => '17', 'code_access' => '55', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 56', 'location_id' => '17', 'code_access' => '56', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 57', 'location_id' => '17', 'code_access' => '57', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 58', 'location_id' => '17', 'code_access' => '58', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 59', 'location_id' => '17', 'code_access' => '59', 'updated_by' => 'maulana ibrahim'],
            ['name' => 'wilayah 60', 'location_id' => '17', 'code_access' => '60', 'updated_by' => 'maulana ibrahim']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_divisions');
    }
}