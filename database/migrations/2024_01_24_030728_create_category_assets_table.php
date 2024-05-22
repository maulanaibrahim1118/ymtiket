<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoryAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('category_assets', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kategori', 50)->unique();
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('category_assets')->insert([
            ['nama_kategori' => "information technology", 'updated_by' => "maulana ibrahim", 'created_at' => '2024-02-21 14:14:38', 'updated_at' => '2024-02-21 14:14:38'],
            ['nama_kategori' => "mechanical electrical", 'updated_by' => "maulana ibrahim", 'created_at' => '2024-02-21 14:14:38', 'updated_at' => '2024-02-21 14:14:38']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_assets');
    }
}