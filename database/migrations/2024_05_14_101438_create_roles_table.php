<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('role_name', 15)->unique();
            $table->bigInteger('code_access')->nullable();
            $table->string('updated_by', 40);
            $table->timestamps();
        });

        DB::table('roles')->insert([
            ['role_name' => 'service desk', 'code_access' => NULL, 'updated_by' => 'maulana ibrahim'],
            ['role_name' => 'agent', 'code_access' => NULL, 'updated_by' => 'maulana ibrahim'],
            ['role_name' => 'client', 'code_access' => NULL, 'updated_by' => 'maulana ibrahim']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}