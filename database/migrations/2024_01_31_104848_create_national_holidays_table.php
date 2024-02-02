<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNationalHolidaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('national_holidays', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal');
            $table->string('keterangan');
        });

        DB::table('national_holidays')->insert([
            ['tanggal' => '01-01-2024', 'keterangan' => 'Tahun Baru 2024 Masehi'],
            ['tanggal' => '08-02-2024', 'keterangan' => 'Isra Mikraj Nabi Muhammad SAW'],
            ['tanggal' => '10-02-2024', 'keterangan' => 'Tahun Baru Imlek 2575 Kongzili'],
            ['tanggal' => '11-03-2024', 'keterangan' => 'Hari Suci Nyepi Tahun Baru Saka 1946'],
            ['tanggal' => '29-03-2024', 'keterangan' => 'Wafat Isa Al Masih'],
            ['tanggal' => '31-03-2024', 'keterangan' => 'Hari Paskah'],
            ['tanggal' => '10-04-2024', 'keterangan' => 'Hari Raya Idulfitri 1445 Hijriah'],
            ['tanggal' => '11-04-2024', 'keterangan' => 'Hari Raya Idulfitri 1445 Hijriah'],
            ['tanggal' => '01-05-2024', 'keterangan' => 'Hari Buruh Internasional'],
            ['tanggal' => '09-05-2024', 'keterangan' => 'Kenaikan Isa Al Masih'],
            ['tanggal' => '23-05-2024', 'keterangan' => 'Hari Raya Waisak 2568 BE'],
            ['tanggal' => '01-06-2024', 'keterangan' => 'Hari Lahir Pancasila'],
            ['tanggal' => '17-06-2024', 'keterangan' => 'Hari Raya Iduladha 1445 Hijriah'],
            ['tanggal' => '07-07-2024', 'keterangan' => 'Tahun Baru Islam 1446 Hijriah'],
            ['tanggal' => '17-08-2024', 'keterangan' => 'Hari Kemerdekaan Republik Indonesia'],
            ['tanggal' => '16-09-2024', 'keterangan' => 'Maulid Nabi Muhammad SAW'],
            ['tanggal' => '25-12-2024', 'keterangan' => 'Hari Raya Natal']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('national_holidays');
    }
}
