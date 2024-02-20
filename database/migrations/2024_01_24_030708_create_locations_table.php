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
            $table->string('nama_lokasi', 50)->unique();
            $table->string('wilayah', 20);
            $table->string('regional', 20);
            $table->string('area', 20);
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('locations')->insert([
            ['nama_lokasi' => 'accounting', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'asset management', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'DC 48', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'DC 53', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'DC gedebage', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'DC mekar raya', 'wilayah' => 'distribution center', 'regional' => 'distribution center', 'area' => 'distribution center', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'finance', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'general affair', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'human resource', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'information technology', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'internal audit', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'inventory control', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'legal', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'marketing', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'merchandising food', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'merchandising non food', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR area 1', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR area 2', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a', 'wilayah' => 'head office', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-1', 'wilayah' => 'wilayah 1', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-2', 'wilayah' => 'wilayah 2', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-3', 'wilayah' => 'wilayah 3', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-4', 'wilayah' => 'wilayah 4', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-5', 'wilayah' => 'wilayah 5', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-6', 'wilayah' => 'wilayah 6', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-7', 'wilayah' => 'wilayah 7', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-8', 'wilayah' => 'wilayah 8', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-9', 'wilayah' => 'wilayah 9', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional a-10', 'wilayah' => 'wilayah 10', 'regional' => 'regional a', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b', 'wilayah' => 'head office', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-11', 'wilayah' => 'wilayah 11', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-12', 'wilayah' => 'wilayah 12', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-13', 'wilayah' => 'wilayah 13', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-14', 'wilayah' => 'wilayah 14', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-15', 'wilayah' => 'wilayah 15', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-16', 'wilayah' => 'wilayah 16', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-17', 'wilayah' => 'wilayah 17', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional b-18', 'wilayah' => 'wilayah 18', 'regional' => 'regional b', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c', 'wilayah' => 'head office', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-19', 'wilayah' => 'wilayah 19', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-20', 'wilayah' => 'wilayah 20', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-21', 'wilayah' => 'wilayah 21', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-22', 'wilayah' => 'wilayah 22', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-23', 'wilayah' => 'wilayah 23', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-24', 'wilayah' => 'wilayah 24', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-25', 'wilayah' => 'wilayah 25', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-26', 'wilayah' => 'wilayah 26', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional c-27', 'wilayah' => 'wilayah 27', 'regional' => 'regional c', 'area' => 'area 1', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d', 'wilayah' => 'head office', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-31', 'wilayah' => 'wilayah 31', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-32', 'wilayah' => 'wilayah 32', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-33', 'wilayah' => 'wilayah 33', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-34', 'wilayah' => 'wilayah 34', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-35', 'wilayah' => 'wilayah 35', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-36', 'wilayah' => 'wilayah 36', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-37', 'wilayah' => 'wilayah 37', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-38', 'wilayah' => 'wilayah 38', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional d-39', 'wilayah' => 'wilayah 39', 'regional' => 'regional d', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e', 'wilayah' => 'head office', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-41', 'wilayah' => 'wilayah 41', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-42', 'wilayah' => 'wilayah 42', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-43', 'wilayah' => 'wilayah 43', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-44', 'wilayah' => 'wilayah 44', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-45', 'wilayah' => 'wilayah 45', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-46', 'wilayah' => 'wilayah 46', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-47', 'wilayah' => 'wilayah 47', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-48', 'wilayah' => 'wilayah 48', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional e-49', 'wilayah' => 'wilayah 49', 'regional' => 'regional e', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f', 'wilayah' => 'head office', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-51', 'wilayah' => 'wilayah 51', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-52', 'wilayah' => 'wilayah 52', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-53', 'wilayah' => 'wilayah 53', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-54', 'wilayah' => 'wilayah 54', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-55', 'wilayah' => 'wilayah 55', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-56', 'wilayah' => 'wilayah 56', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-57', 'wilayah' => 'wilayah 57', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-58', 'wilayah' => 'wilayah 58', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-59', 'wilayah' => 'wilayah 59', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'OPR regional f-60', 'wilayah' => 'wilayah 60', 'regional' => 'regional f', 'area' => 'area 2', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'other income', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'procurement', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'project controller', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'project me', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'project sipil', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'sales development', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'simo', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'store development', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'tax', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim'],
            ['nama_lokasi' => 'visual', 'wilayah' => 'head office', 'regional' => 'head office', 'area' => 'head office', 'updated_by' => 'maulana ibrahim']
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
