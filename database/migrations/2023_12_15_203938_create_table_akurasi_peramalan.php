<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akurasi_peramalan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_categories');
            $table->integer('tipe');
            $table->float('alpha');
            $table->float('beta');
            $table->float('rsme');
            $table->float('smape');
            $table->float('akurasi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akurasi_peramalan');
    }
};
