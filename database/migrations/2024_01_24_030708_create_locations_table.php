<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('site')->nullable();
            $table->string('initial', 5)->nullable();
            $table->string('nama_lokasi', 50)->unique();
            $table->bigInteger('wilayah_id');
            $table->enum('is_active', ['0', '1'])->default('1');
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('locations')->insert([
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'accounting', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'asset management', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'DC 48', 'wilayah_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'DC 53', 'wilayah_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'DC gedebage', 'wilayah_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'DC mekar raya', 'wilayah_id' => 1, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'finance', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'general affair', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'human resource', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'information technology', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'internal audit', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'inventory control', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'legal', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'marketing', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'merchandising food', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'merchandising non food', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'operational', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'other income', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'procurement', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'project controller', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'project me', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'project sipil', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'sales development', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'simo', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'store development', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'tax', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim'],
            ['site' => NULL, 'initial' => NULL, 'nama_lokasi' => 'visual', 'wilayah_id' => 2, 'updated_by' => 'maulana ibrahim']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locations');
    }
}