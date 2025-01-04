<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis',['baru','lama']);
            $table->string('nama');
            $table->string('tgl_daftar');
            $table->string('no_hp');
            $table->string('ktp');
            $table->string('titik_koordinat')->nullable();
            $table->string('alamat');
            $table->string('status')->default('aktif');
            $table->string('service')->default('default');
            $table->string('profile')->default('default');
            $table->string('username_pppoe');
            $table->string('local_address');
            $table->string('remote_address');
            // konfigurasi mikrotik
            $table->bigInteger('router_id');
            $table->enum('metode',['buat_baru','sinkronisasi']);
            $table->bigInteger('package_id');
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
        Schema::dropIfExists('pelanggans');
    }
};
