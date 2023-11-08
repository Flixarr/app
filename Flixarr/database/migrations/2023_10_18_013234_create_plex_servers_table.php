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
        Schema::create('plex_servers', function (Blueprint $table) {
            $table->id();
            $table->string('server_id')->nullable();
            $table->string('name')->nullable();
            $table->string('host');
            $table->string('port');
            $table->string('scheme');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plex_servers');
    }
};
