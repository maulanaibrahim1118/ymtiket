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
            $table->string('inisial', 3)->unique();
            $table->string('nama_lokasi', 50)->unique();
            $table->string('wilayah', 20);
            $table->string('regional', 20);
            $table->string('area', 20);
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('locations')->insert([
            ['inisial' => 'acc', 'nama_lokasi' => 'accounting', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'asm', 'nama_lokasi' => 'asset management', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'd48', 'nama_lokasi' => 'DC 48', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'd53', 'nama_lokasi' => 'DC 53', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'dcg', 'nama_lokasi' => 'DC gedebage', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'dmr', 'nama_lokasi' => 'DC mekar raya', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'fnc', 'nama_lokasi' => 'finance', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'ga', 'nama_lokasi' => 'general affair', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'hr', 'nama_lokasi' => 'human resource', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'it', 'nama_lokasi' => 'information technology', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'aud', 'nama_lokasi' => 'internal audit', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'ic', 'nama_lokasi' => 'intventory control', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'lgl', 'nama_lokasi' => 'legal', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'mkt', 'nama_lokasi' => 'marketing', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'mdf', 'nama_lokasi' => 'merchandising food', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'mnf', 'nama_lokasi' => 'merchandising non food', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'oa1', 'nama_lokasi' => 'operational area 1', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'oa2', 'nama_lokasi' => 'operational area 2', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'ora', 'nama_lokasi' => 'operational reg. a', 'wilayah' => 'head office', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'orb', 'nama_lokasi' => 'operational reg. b', 'wilayah' => 'head office', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'orc', 'nama_lokasi' => 'operational reg. c', 'wilayah' => 'head office', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'ord', 'nama_lokasi' => 'operational reg. d', 'wilayah' => 'head office', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'ore', 'nama_lokasi' => 'operational reg. e', 'wilayah' => 'head office', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'orf', 'nama_lokasi' => 'operational reg. f', 'wilayah' => 'head office', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'oi', 'nama_lokasi' => 'other income', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'prc', 'nama_lokasi' => 'procurement', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'pco', 'nama_lokasi' => 'project controller', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'me', 'nama_lokasi' => 'project me', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'sip', 'nama_lokasi' => 'project sipil', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'sdd', 'nama_lokasi' => 'sales development', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'smo', 'nama_lokasi' => 'simo', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'std', 'nama_lokasi' => 'store development', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'tax', 'nama_lokasi' => 'tax', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['inisial' => 'vis', 'nama_lokasi' => 'visual', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim']
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
