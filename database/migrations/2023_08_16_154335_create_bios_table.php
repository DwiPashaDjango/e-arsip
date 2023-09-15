<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id');
            $table->foreignId('province_id');
            $table->foreignId('regencie_id');
            $table->foreignId('district_id');
            $table->foreignId('village_id');
            $table->string('kepsek');
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            $table->enum('akreditasi', ['A', 'B', 'C']);
            $table->longText('alamat')->nullable();
            $table->string('avatars')->default('default.png');
            $table->enum('status_sekolah', ['Negeri', 'Swasta']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bios');
    }
}
