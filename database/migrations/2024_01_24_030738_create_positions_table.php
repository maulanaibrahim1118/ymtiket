<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan', 20)->unique();
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('positions')->insert([
            ['nama_jabatan' => 'Admin', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Chief', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Direktur', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Junior Staff', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Kepala Toko', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Koordinator Wilayah', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Manager', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Senior Manager', 'updated_by' => 'maulana ibrahim'],
            ['nama_jabatan' => 'Staff', 'updated_by' => 'maulana ibrahim']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
