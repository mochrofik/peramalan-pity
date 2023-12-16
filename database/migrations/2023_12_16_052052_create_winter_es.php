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
        Schema::create('winter_es', function (Blueprint $table) {
            $table->id();
            $table->integer('tahun');
            $table->integer('id_categories');
            $table->float('produksi');
            $table->float('xt');
            $table->float('st');
            $table->float('bt');
            $table->float('lmt_l');
            $table->float('lmt');
            $table->float('forecast');
            $table->float('error');
            $table->float('error2');
            $table->float('smape');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('winter_es');
    }
};
